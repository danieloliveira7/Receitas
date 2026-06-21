<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['login' => 'daniel'],
            [
                'nome'     => 'Daniel Oliveira',
                'email'    => 'daniel.oliveira1@universo.univates.br',
                'senha'    => Hash::make('teste123'),
                'situacao' => 'ativo',
            ]
        );
    }
}
