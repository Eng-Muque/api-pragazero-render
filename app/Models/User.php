<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 1. Verifique se esta linha de baixo existe:
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    // 2. Adicione 'HasApiTokens' dentro da classe, como abaixo:
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'telefone',
        'password',
        'role', // Verifique se o role está aqui também
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Aproveite e adicione também o de Orçamentos (Quotations)
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }
}
