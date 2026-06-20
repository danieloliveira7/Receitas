<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceitaCriacaoTest extends TestCase
{
    use RefreshDatabase;

    private function fazerLogin(): void
    {
        $usuario = Usuario::create([
            'nome'     => 'Teste',
            'login'    => 'teste',
            'senha'    => bcrypt('123456'),
            'situacao' => 'ativo',
        ]);
        $this->withSession(['usuario' => $usuario]);
    }

    /** @test */
    public function pode_criar_uma_receita_valida(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Bolo de Cenoura',
            'descricao'     => 'Receita clássica de bolo de cenoura',
            'data_registro' => '2024-01-15',
            'custo'         => 25.50,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertRedirect(route('receitas.index'));
        $resposta->assertSessionHas('sucesso');
        $this->assertDatabaseHas('receitas', ['nome' => 'Bolo de Cenoura']);
    }

    /** @test */
    public function nome_e_obrigatorio_na_criacao(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => '',
            'descricao'     => 'Alguma descrição',
            'data_registro' => '2024-01-15',
            'custo'         => 10.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('nome');
    }

    /** @test */
    public function descricao_e_obrigatoria_na_criacao(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Receita X',
            'descricao'     => '',
            'data_registro' => '2024-01-15',
            'custo'         => 10.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('descricao');
    }
}
