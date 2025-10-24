<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importe o BelongsTo

class Subscription extends Model
{
    use HasFactory;

    /**
     * Define o relacionamento: Um Contrato (Subscription) pertence a um Plano (Plan).
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Define o relacionamento: Um Contrato (Subscription) pertence a um Usuário (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}