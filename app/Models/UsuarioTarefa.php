<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class UsuarioTarefa extends BaseModel
{
    use HasFactory;

    protected $table = 'usuario_tarefa';
    protected $fillable = [
        'id_usuario',
        'id_tarefa',
        'status'
    ];

    protected $hidden = [];
    
    protected $casts = [
        'id_usuario' => 'string',
        'id_tarefa' => 'string',
        'id' => 'string'
    ];
}
