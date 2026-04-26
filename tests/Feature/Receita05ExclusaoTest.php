<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita05ExclusaoTest extends TestCase
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
    public function pode_excluir_uma_receita(): void
    {
        $this->fazerLogin();

        $receita = Receita::create([
            'nome'          => 'Para Excluir',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 10,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta = $this->delete(route('receitas.destroy', $receita));

        $resposta->assertRedirect(route('receitas.index'));
        $this->assertDatabaseMissing('receitas', ['id' => $receita->id]);
    }

    /** @test */
    public function excluir_receita_inexistente_retorna_404(): void
    {
        $this->fazerLogin();

        $resposta = $this->delete(route('receitas.destroy', 9999));

        $resposta->assertNotFound();
    }

    /** @test */
    public function mensagem_de_sucesso_apos_exclusao(): void
    {
        $this->fazerLogin();

        $receita = Receita::create([
            'nome'          => 'Apagar',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 5,
            'tipo_receita'  => 'salgada',
            'status'        => 'inativo',
        ]);

        $resposta = $this->delete(route('receitas.destroy', $receita));

        $resposta->assertSessionHas('sucesso');
    }
}
