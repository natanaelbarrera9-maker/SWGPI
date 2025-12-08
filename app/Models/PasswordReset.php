<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    use HasFactory;

    /**
     * Indica si el modelo tiene timestamps
     */
    public $timestamps = true;

    /**
     * Actualizar solo created_at
     */
    const UPDATED_AT = null;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'password_resets';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Obtener el usuario al que pertenece este token de reset
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope para obtener tokens válidos (no expirados)
     */
    public function scopeValidos($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope para obtener tokens expirados
     */
    public function scopeExpirados($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Verificar si el token está expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    /**
     * Verificar si el token es válido
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }
}
