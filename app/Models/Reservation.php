<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'client_id',
        'prestataire_id',
        'service_id',
        'date_prevue',
        'type_demande',
        'status',
        'adresse_intervention',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'date_prevue' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public const STATUS_EN_ATTENTE = 'en_attente';
    public const STATUS_ACCEPTE = 'accepte';
    public const STATUS_REFUSE = 'refuse';
    public const STATUS_EN_ROUTE = 'en_route';
    public const STATUS_TERMINE = 'termine';
    public const STATUS_ANNULE = 'annule';

    public const TYPE_IMMEDIATE = 'immediate';
    public const TYPE_PROGRAMMEE = 'programmee';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'prestataire_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function avis(): HasOne
    {
        return $this->hasOne(Avis::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_EN_ATTENTE, self::STATUS_ACCEPTE], true);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === self::STATUS_TERMINE && ! $this->avis;
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_ACCEPTE   => 'Accepté',
            self::STATUS_REFUSE    => 'Refusé',
            self::STATUS_EN_ROUTE  => 'En route',
            self::STATUS_TERMINE   => 'Terminé',
            self::STATUS_ANNULE    => 'Annulé',
            default => $status,
        };
    }
}
