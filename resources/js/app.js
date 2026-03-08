// ============================================================
//  ServeMe — app.js
//  Stack: Alpine.js v3 · Pusher · Leaflet.js · Axios
// ============================================================

// --- 1. Imports (Vite / Laravel Mix) -----------------------
import Alpine  from 'alpinejs'
import intersect from '@alpinejs/intersect'
import focus    from '@alpinejs/focus'
import collapse from '@alpinejs/collapse'
import Pusher   from 'pusher-js'
import axios    from 'axios'
import L        from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Fix Leaflet default icon paths broken by Webpack/Vite
import markerIcon2x   from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon     from 'leaflet/dist/images/marker-icon.png'
import markerShadow   from 'leaflet/dist/images/marker-shadow.png'
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl:       markerIcon,
    shadowUrl:     markerShadow,
})

// --- 2. Alpine Plugins -------------------------------------
Alpine.plugin(intersect)
Alpine.plugin(focus)
Alpine.plugin(collapse)

// --- 3. Axios Global Config --------------------------------
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Accept']           = 'application/json'

// CSRF token from meta tag (set in <head> by layout)
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
if (csrfToken) axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken

// --- 4. Pusher Setup (Real-Time Notifications) -------------
const pusherKey    = import.meta.env.VITE_PUSHER_APP_KEY
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu'

let pusherClient = null

function getPusher() {
    if (!pusherClient && pusherKey) {
        pusherClient = new Pusher(pusherKey, {
            cluster: pusherCluster,
            authEndpoint: '/broadcasting/auth',
            auth: { headers: { 'X-CSRF-TOKEN': csrfToken } },
        })
    }
    return pusherClient
}

// --- 5. Alpine Global Stores --------------------------------

// ---- 5.1 Toast / Notifications store ----------------------
Alpine.store('toast', {
    items: [],

    /**
     * Show a toast message
     * @param {string} message
     * @param {'success'|'error'|'info'} type
     * @param {number} duration  ms
     */
    show(message, type = 'info', duration = 4000) {
        const id = Date.now()
        this.items.push({ id, message, type })
        setTimeout(() => this.dismiss(id), duration)
    },

    dismiss(id) {
        this.items = this.items.filter(i => i.id !== id)
    },

    success(msg) { this.show(msg, 'success') },
    error(msg)   { this.show(msg, 'error') },
    info(msg)    { this.show(msg, 'info') },
})

// ---- 5.2 Auth / User store --------------------------------
Alpine.store('auth', {
    user: window.__AUTH_USER__ ?? null,    // injected from blade: window.__AUTH_USER__
    role: window.__AUTH_ROLE__ ?? null,    // 'client' | 'provider' | 'admin'

    isLoggedIn()  { return !!this.user },
    isClient()    { return this.role === 'client' },
    isProvider()  { return this.role === 'provider' },
    isAdmin()     { return this.role === 'admin' },
})

// ---- 5.3 Notifications store (real-time via Pusher) -------
Alpine.store('notifications', {
    items: [],
    unread: 0,

    init() {
        // Load existing from server on boot
        if (!Alpine.store('auth').isLoggedIn()) return
        axios.get('/api/notifications').then(r => {
            this.items  = r.data.data  ?? []
            this.unread = r.data.unread ?? 0
        }).catch(() => {})

        // Subscribe to private channel
        const userId = Alpine.store('auth').user?.id
        if (!userId) return

        const pusher  = getPusher()
        if (!pusher) return

        const channel = pusher.subscribe(`private-user.${userId}`)

        channel.bind('reservation.accepted', (data) => {
            this.push(data.notification)
            Alpine.store('toast').success(data.notification.message)
            this.ringBell()
        })
        channel.bind('reservation.refused', (data) => {
            this.push(data.notification)
            Alpine.store('toast').error(data.notification.message)
            this.ringBell()
        })
        channel.bind('reservation.status', (data) => {
            this.push(data.notification)
            Alpine.store('toast').info(data.notification.message)
            this.ringBell()
        })
        channel.bind('new.request', (data) => {
            this.push(data.notification)
            Alpine.store('toast').info(data.notification.message)
            this.ringBell()
        })
    },

    push(notif) {
        this.items.unshift(notif)
        this.unread++
    },

    markAllRead() {
        this.unread = 0
        axios.post('/api/notifications/read-all').catch(() => {})
    },

    ringBell() {
        const bell = document.getElementById('notif-bell')
        if (bell) {
            bell.classList.add('animate-ring')
            setTimeout(() => bell.classList.remove('animate-ring'), 1000)
        }
    },
})

// ---- 5.4 Cart / Reservation draft store ------------------
Alpine.store('reservation', {
    category:   null,
    service:    null,
    provider:   null,
    type:       'immediate',   // 'immediate' | 'scheduled'
    date:       null,
    time:       null,
    address:    '',
    lat:        null,
    lng:        null,

    setCategory(cat)  { this.category  = cat; this.service = null; this.provider = null },
    setService(svc)   { this.service   = svc; this.provider = null },
    setProvider(p)    { this.provider  = p },
    setLocation(lat, lng, addr) { this.lat = lat; this.lng = lng; this.address = addr },

    isReady() {
        if (!this.service || !this.provider || !this.address) return false
        if (this.type === 'scheduled' && (!this.date || !this.time)) return false
        return true
    },

    reset() {
        Object.assign(this, {
            category: null, service: null, provider: null,
            type: 'immediate', date: null, time: null,
            address: '', lat: null, lng: null,
        })
    },
})

// --- 6. Alpine Components ----------------------------------

// ---- 6.1 Navbar ------------------------------------------
Alpine.data('navbar', () => ({
    open: false,
    scrolled: false,

    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20
        }, { passive: true })

        Alpine.store('notifications').init()
    },
}))

// ---- 6.2 Map Component (Leaflet) -------------------------
Alpine.data('serveMap', (options = {}) => ({
    map:          null,
    markers:      [],
    userMarker:   null,
    lat:          options.lat  ?? 33.5731,   // Default: Casablanca
    lng:          options.lng  ?? -7.5898,
    zoom:         options.zoom ?? 13,
    providers:    options.providers ?? [],

    init() {
        this.$nextTick(() => this.initMap())
    },

    initMap() {
        const el = this.$el.querySelector('[data-map]') ?? this.$el
        if (!el) return

        this.map = L.map(el, { zoomControl: true }).setView([this.lat, this.lng], this.zoom)

        // OpenStreetMap tiles (free, no API key)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(this.map)

        // Custom teal icon
        this.tealIcon = L.divIcon({
            html: `<div class="leaflet-marker-serveme">
                       <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                       </svg>
                   </div>`,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
        })

        // Place providers
        this.providers.forEach(p => this.addProviderMarker(p))

        // Try get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => this.setUserLocation(pos.coords.latitude, pos.coords.longitude),
                ()  => {}, // silent fail
                { timeout: 8000 }
            )
        }
    },

    addProviderMarker(provider) {
        if (!provider.lat || !provider.lng) return
        const marker = L.marker([provider.lat, provider.lng], { icon: this.tealIcon })
            .addTo(this.map)
            .bindPopup(`
                <div class="text-sm font-semibold text-slate-800">${provider.name}</div>
                <div class="text-xs text-slate-500">${provider.service ?? ''}</div>
                <div class="flex gap-0.5 mt-1 text-amber-400 text-xs">
                    ${'★'.repeat(Math.round(provider.rating ?? 0))}
                </div>
            `, { maxWidth: 200 })
        this.markers.push(marker)
    },

    setUserLocation(lat, lng) {
        this.lat = lat
        this.lng = lng

        if (this.userMarker) this.map.removeLayer(this.userMarker)

        this.userMarker = L.circleMarker([lat, lng], {
            radius: 8, fillColor: '#0f766e', color: '#fff',
            weight: 2, opacity: 1, fillOpacity: 0.9,
        }).addTo(this.map).bindPopup('Votre position')

        this.map.setView([lat, lng], 14)

        // Emit to Alpine store
        Alpine.store('reservation').setLocation(lat, lng, '')

        // Reverse geocode with Nominatim
        this.reverseGeocode(lat, lng)
    },

    async reverseGeocode(lat, lng) {
        try {
            const res = await axios.get(`https://nominatim.openstreetmap.org/reverse`, {
                params: { lat, lon: lng, format: 'json', 'accept-language': 'fr' }
            })
            const addr = res.data.display_name ?? ''
            Alpine.store('reservation').setLocation(lat, lng, addr)
        } catch (_) {}
    },

    async searchAddress(query) {
        if (query.length < 3) return []
        try {
            const res = await axios.get('https://nominatim.openstreetmap.org/search', {
                params: { q: query, format: 'json', limit: 5, 'accept-language': 'fr' }
            })
            return res.data.map(r => ({
                label: r.display_name,
                lat:   parseFloat(r.lat),
                lng:   parseFloat(r.lon),
            }))
        } catch (_) { return [] }
    },

    flyTo(lat, lng) {
        this.map?.flyTo([lat, lng], 15, { animate: true, duration: 0.8 })
    },
}))

// ---- 6.3 Address Autocomplete (Nominatim / OSM) ----------
Alpine.data('addressSearch', () => ({
    query:       '',
    results:     [],
    loading:     false,
    selected:    null,
    debounceTimer: null,

    onInput() {
        clearTimeout(this.debounceTimer)
        this.debounceTimer = setTimeout(() => this.search(), 350)
    },

    async search() {
        if (this.query.length < 3) { this.results = []; return }
        this.loading = true
        try {
            const res = await axios.get('https://nominatim.openstreetmap.org/search', {
                params: { q: this.query, format: 'json', limit: 5, 'accept-language': 'fr' }
            })
            this.results = res.data.map(r => ({
                label: r.display_name,
                lat:   parseFloat(r.lat),
                lng:   parseFloat(r.lon),
            }))
        } catch (_) {
            this.results = []
        }
        this.loading = false
    },

    pick(result) {
        this.selected = result
        this.query    = result.label
        this.results  = []
        Alpine.store('reservation').setLocation(result.lat, result.lng, result.label)
        this.$dispatch('location-selected', result)
    },
}))

// ---- 6.4 Reservation Wizard (client flow) ----------------
Alpine.data('reservationWizard', () => ({
    step: 1,
    totalSteps: 4,
    submitting: false,
    errors: {},

    next() { if (this.step < this.totalSteps) this.step++ },
    prev() { if (this.step > 1) this.step-- },

    stepLabel() {
        return ['Catégorie', 'Prestataire', 'Détails', 'Confirmation'][this.step - 1] ?? ''
    },

    async submit() {
        if (!Alpine.store('reservation').isReady()) {
            Alpine.store('toast').error('Veuillez compléter toutes les informations.')
            return
        }
        this.submitting = true
        this.errors = {}
        try {
            const res = await axios.post('/reservations', Alpine.store('reservation'))
            Alpine.store('toast').success('Demande envoyée ! En attente de confirmation.')
            Alpine.store('reservation').reset()
            this.step = 1
            window.location.href = `/reservations/${res.data.id}`
        } catch (err) {
            this.errors = err.response?.data?.errors ?? {}
            Alpine.store('toast').error('Une erreur est survenue. Vérifiez le formulaire.')
        }
        this.submitting = false
    },
}))

// ---- 6.5 Provider Availability Toggle --------------------
Alpine.data('availabilityToggle', (initialStatus = false) => ({
    available: initialStatus,
    loading: false,

    async toggle() {
        this.loading = true
        try {
            const res = await axios.patch('/provider/availability', {
                available: !this.available
            })
            this.available = res.data.available
            Alpine.store('toast').success(
                this.available ? 'Vous êtes maintenant disponible ✓' : 'Vous êtes indisponible'
            )
        } catch (_) {
            Alpine.store('toast').error('Erreur lors de la mise à jour.')
        }
        this.loading = false
    },
}))

// ---- 6.6 Star Rating Component ---------------------------
Alpine.data('starRating', (initial = 0) => ({
    value:    initial,
    hovered:  0,

    rate(n)   { this.value = n },
    hover(n)  { this.hovered = n },
    unhover() { this.hovered = 0 },

    icon(n) {
        return (this.hovered || this.value) >= n ? 'text-amber-400' : 'text-slate-300'
    },
}))

// ---- 6.7 Scroll reveal (IntersectionObserver) -----------
Alpine.data('reveal', (delay = 0) => ({
    visible: false,
    delay,

    init() {
        const observer = new IntersectionObserver(
            ([entry]) => { if (entry.isIntersecting) { this.visible = true; observer.disconnect() } },
            { threshold: 0.15 }
        )
        observer.observe(this.$el)
    },
}))

// ---- 6.8 Counter animation (stats section) ---------------
Alpine.data('counter', (target = 0, duration = 1500) => ({
    value: 0,
    target,

    init() {
        const observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                this.animate()
                observer.disconnect()
            }
        }, { threshold: 0.5 })
        observer.observe(this.$el)
    },

    animate() {
        const start     = performance.now()
        const easeOut   = t => 1 - Math.pow(1 - t, 3)
        const step      = (now) => {
            const progress  = Math.min((now - start) / this.duration_ms, 1)
            this.value      = Math.round(easeOut(progress) * this.target)
            if (progress < 1) requestAnimationFrame(step)
        }
        this.duration_ms = duration
        requestAnimationFrame(step)
    },
}))

// --- 7. Start Alpine -----------------------------------------
window.Alpine = Alpine
Alpine.start()

// --- 8. Global helpers on window ---------------------------
window.ServeMe = {
    toast:       () => Alpine.store('toast'),
    auth:        () => Alpine.store('auth'),
    reservation: () => Alpine.store('reservation'),
    getPusher,
}