<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita04AtualizacaoTest extends TestCase
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

    private function criarReceita(array $attrs = []): Receita
    {
        return Receita::create(array_merge([
            'nome'          => 'Receita Original',
            'descricao'     => 'Desc original',
            'data_registro' => '2024-01-15',
            'custo'         => 20.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ], $attrs));
    }

    /** @test */
    public function pode_atualizar_uma_receita(): void
    {
        $this->fazerLogin();
        $receita = $this->criarReceita();

        $resposta = $this->put(route('receitas.update', $receita), [
            'nome'          => 'Receita Atualizada',
            'descricao'     => 'Nova descrição',
            'data_registro' => '2024-03-10',
            'custo'         => 35.00,
            'tipo_receita'  => 'salgada',
            'status'        => 'inativo',
        ]);

        $resposta->assertRedirect(route('receitas.index'));
        $this->assertDatabaseHas('receitas', ['nome' => 'Receita Atualizada', 'custo' => 35.00]);
        $this->assertDatabaseMissing('receitas', ['nome' => 'Receita Original']);
    }

    /** @test */
    public function formulario_de_edicao_carrega_com_dados_preenchidos(): void
    {
        $this->fazerLogin();
        $receita = $this->criarReceita();

        $resposta = $this->get(route('receitas.edit', $receita));

        $resposta->assertOk();
        $resposta->assertViewIs('receitas.edit');
        $resposta->assertViewHas('receita', $receita);
    }

    /** @test */
    public function atualizacao_com_nome_vazio_falha(): void
    {
        $this->fazerLogin();
        $receita = $this->criarReceita();

        $resposta = $this->put(route('receitas.update', $receita), [
            'nome'          => '',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 10,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta->assertSessionHasErrors('nome');
        $this->assertDatabaseHas('receitas', ['nome' => 'Receita Original']);
    }
}
