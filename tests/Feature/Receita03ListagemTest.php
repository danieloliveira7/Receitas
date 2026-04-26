<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita03ListagemTest extends TestCase
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
    public function pagina_de_listagem_carrega_com_sucesso(): void
    {
        $this->fazerLogin();

        $resposta = $this->get(route('receitas.index'));

        $resposta->assertOk();
        $resposta->assertViewIs('receitas.index');
    }

    /** @test */
    public function filtra_receitas_por_status_ativo(): void
    {
        $this->fazerLogin();

        Receita::create(['nome' => 'Ativa', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Inativa', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'inativo']);

        $resposta = $this->get(route('receitas.index', ['status' => 'ativo']));

        $resposta->assertSee('Ativa');
        $resposta->assertDontSee('Inativa');
    }

    /** @test */
    public function filtra_receitas_por_tipo_doce(): void
    {
        $this->fazerLogin();

        Receita::create(['nome' => 'Torta Doce', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Coxinha', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 5, 'tipo_receita' => 'salgada', 'status' => 'ativo']);

        $resposta = $this->get(route('receitas.index', ['tipo_receita' => 'doce']));

        $resposta->assertSee('Torta Doce');
        $resposta->assertDontSee('Coxinha');
    }

    /** @test */
    public function filtra_receitas_por_data(): void
    {
        $this->fazerLogin();

        Receita::create(['nome' => 'Janeiro', 'descricao' => 'D', 'data_registro' => '2024-01-10', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Dezembro', 'descricao' => 'D', 'data_registro' => '2024-12-10', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);

        $resposta = $this->get(route('receitas.index', [
            'data_inicio' => '2024-01-01',
            'data_fim'    => '2024-06-30',
        ]));

        $resposta->assertSee('Janeiro');
        $resposta->assertDontSee('Dezembro');
    }
}
