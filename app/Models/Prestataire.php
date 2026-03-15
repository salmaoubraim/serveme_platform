<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestataire extends Model
{
    public $timestamps = false;

    protected $table = 'prestataires';

    protected $fillable = [
        'id',
        'description',
        'availability',
        'localisation',
        'latitude',
        'longitude',
        'note_moyenne',
        'is_validated',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'note_moyenne' => 'float',
            'is_validated' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'prestataire_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'prestataire_id');
    }

    public function disponibilites(): HasMany
    {
        return $this->hasMany(Disponibilite::class, 'prestataire_id');
    }
}
