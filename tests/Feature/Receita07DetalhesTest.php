<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita07DetalhesTest extends TestCase
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
    public function pagina_show_exibe_dados_da_receita(): void
    {
        $this->fazerLogin();

        $receita = Receita::create([
            'nome'          => 'Pudim de Leite',
            'descricao'     => 'Receita tradicional de pudim',
            'data_registro' => '2024-06-01',
            'custo'         => 18.75,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $resposta = $this->get(route('receitas.show', $receita));

        $resposta->assertOk();
        $resposta->assertSee('Pudim de Leite');
        $resposta->assertSee('Receita tradicional de pudim');
    }

    /** @test */
    public function show_de_receita_inexistente_retorna_404(): void
    {
        $this->fazerLogin();

        $resposta = $this->get(route('receitas.show', 99999));

        $resposta->assertNotFound();
    }
}
