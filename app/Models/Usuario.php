<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'login',
        'email',
        'senha',
        'situacao',
    ];

    protected $hidden = ['senha'];

    public $timestamps = true;
}