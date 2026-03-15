<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    public $timestamps = false;

    protected $table = 'avis';

    const CREATED_AT = 'date_avis';

    protected $fillable = [
        'reservation_id',
        'client_id',
        'prestataire_id',
        'note',
        'commentaire',
        'date_avis',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'prestataire_id');
    }
}
