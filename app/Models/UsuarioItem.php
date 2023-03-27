<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class UsuarioItem extends BaseModel
{
    use HasFactory;

    protected $table = 'usuario_item';
    protected $fillable = [
        'id_usuario',
        'id_item',
    ];

    protected $hidden = [];
    
    protected $casts = [
        'id' => 'string'
    ];
}
