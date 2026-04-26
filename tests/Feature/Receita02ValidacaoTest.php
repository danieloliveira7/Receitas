<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita02ValidacaoTest extends TestCase
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
    public function custo_negativo_e_invalido(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Receita X',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-15',
            'custo'         => -5.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('custo');
    }

    /** @test */
    public function tipo_receita_invalido_e_rejeitado(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Receita X',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-15',
            'custo'         => 10.00,
            'tipo_receita'  => 'invalido',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('tipo_receita');
    }

    /** @test */
    public function status_invalido_e_rejeitado(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Receita X',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-15',
            'custo'         => 10.00,
            'tipo_receita'  => 'doce',
            'status'        => 'publicado',
        ]);

        $resposta->assertSessionHasErrors('status');
    }

    /** @test */
    public function data_invalida_e_rejeitada(): void
    {
        $this->fazerLogin();

        $resposta = $this->post(route('receitas.store'), [
            'nome'          => 'Receita X',
            'descricao'     => 'Desc',
            'data_registro' => 'nao-e-uma-data',
            'custo'         => 10.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('data_registro');
    }
}
