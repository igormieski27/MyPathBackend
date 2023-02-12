<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class SaidaProduto extends BaseModel
{
    use HasFactory;

    protected $table = 'saidas_produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_saida',
        'id_produto',
        'unidade_medida',
        'quantidade',
        'valor_unitario',
        'desconto',
        'valor_total'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'id_saida' => 'string',
        'id_produto' => 'string',
    ];
}
