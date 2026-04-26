<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita08ExportacaoPdfTest extends TestCase
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
    public function exportacao_pdf_retorna_html_com_receitas(): void
    {
        $this->fazerLogin();

        Receita::create([
            'nome'          => 'Frango Grelhado',
            'descricao'     => 'Saudável e gostoso',
            'data_registro' => '2024-05-10',
            'custo'         => 30.00,
            'tipo_receita'  => 'salgada',
            'status'        => 'ativo',
        ]);

        $resposta = $this->get(route('receitas.pdf'));

        $resposta->assertOk();
        $resposta->assertSee('Frango Grelhado');
        $resposta->assertSee('Relatório de Receitas');
    }

    /** @test */
    public function exportacao_pdf_respeita_filtro_de_status(): void
    {
        $this->fazerLogin();

        Receita::create(['nome' => 'Ativa', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Inativa', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'inativo']);

        $resposta = $this->get(route('receitas.pdf', ['status' => 'ativo']));

        $resposta->assertSee('Ativa');
        $resposta->assertDontSee('Inativa');
    }

    /** @test */
    public function exportacao_pdf_sem_autenticacao_e_bloqueada(): void
    {
        $resposta = $this->get(route('receitas.pdf'));

        $resposta->assertRedirect(route('login'));
    }
}
