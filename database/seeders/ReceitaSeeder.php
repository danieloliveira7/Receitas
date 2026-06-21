<?php

namespace Database\Seeders;

use App\Models\Receita;
use Illuminate\Database\Seeder;

class ReceitaSeeder extends Seeder
{
    public function run(): void
    {
        $receitas = [
            ['nome' => 'Brigadeiro',      'descricao' => 'Doce de chocolate', 'data_registro' => '2026-04-08', 'custo' => 10.00, 'tipo_receita' => 'doce',    'status' => 'ativo'],
            ['nome' => 'Coxinha',         'descricao' => 'Frango',            'data_registro' => '2026-04-08', 'custo' => 15.00, 'tipo_receita' => 'salgada', 'status' => 'ativo'],
            ['nome' => 'Bolo de cenoura', 'descricao' => 'Com cobertura',     'data_registro' => '2026-04-08', 'custo' => 20.00, 'tipo_receita' => 'doce',    'status' => 'ativo'],
            ['nome' => 'Pastel',          'descricao' => 'Carne',             'data_registro' => '2026-04-08', 'custo' => 12.00, 'tipo_receita' => 'salgada', 'status' => 'ativo'],
            ['nome' => 'Pudim',           'descricao' => 'Leite condensado',  'data_registro' => '2026-04-08', 'custo' => 18.00, 'tipo_receita' => 'doce',    'status' => 'ativo'],
            ['nome' => 'Empada',          'descricao' => 'Frango',            'data_registro' => '2026-04-08', 'custo' => 14.00, 'tipo_receita' => 'salgada', 'status' => 'ativo'],
            ['nome' => 'Beijinho',        'descricao' => 'Coco',              'data_registro' => '2026-04-08', 'custo' => 9.00,  'tipo_receita' => 'doce',    'status' => 'ativo'],
            ['nome' => 'Kibe',            'descricao' => 'Carne moída',       'data_registro' => '2026-04-08', 'custo' => 16.00, 'tipo_receita' => 'salgada', 'status' => 'ativo'],
            ['nome' => 'Mousse',          'descricao' => 'Maracujá',          'data_registro' => '2026-04-08', 'custo' => 13.00, 'tipo_receita' => 'doce',    'status' => 'ativo'],
            ['nome' => 'Enroladinho',     'descricao' => 'Salsicha',          'data_registro' => '2026-04-08', 'custo' => 11.00, 'tipo_receita' => 'salgada', 'status' => 'ativo'],
        ];

        foreach ($receitas as $receita) {
            Receita::firstOrCreate(['nome' => $receita['nome']], $receita);
        }
    }
}
