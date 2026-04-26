<?php

namespace Tests\Unit;

use App\Models\Receita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Receita10ModelScopesTest extends TestCase
{
    use RefreshDatabase;

    private function criar(array $attrs = []): Receita
    {
        return Receita::create(array_merge([
            'nome'          => 'Receita',
            'descricao'     => 'Desc',
            'data_registro' => '2024-06-15',
            'custo'         => 10.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ], $attrs));
    }

    /** @test */
    public function scope_por_status_filtra_corretamente(): void
    {
        $this->criar(['nome' => 'A', 'status' => 'ativo']);
        $this->criar(['nome' => 'B', 'status' => 'inativo']);

        $ativos = Receita::porStatus('ativo')->get();

        $this->assertCount(1, $ativos);
        $this->assertEquals('A', $ativos->first()->nome);
    }

    /** @test */
    public function scope_por_tipo_filtra_corretamente(): void
    {
        $this->criar(['nome' => 'Doce', 'tipo_receita' => 'doce']);
        $this->criar(['nome' => 'Salgada', 'tipo_receita' => 'salgada']);

        $doces = Receita::porTipo('doce')->get();

        $this->assertCount(1, $doces);
        $this->assertEquals('Doce', $doces->first()->nome);
    }

    /** @test */
    public function scope_por_data_filtra_intervalo(): void
    {
        $this->criar(['nome' => 'Janeiro', 'data_registro' => '2024-01-10']);
        $this->criar(['nome' => 'Julho',   'data_registro' => '2024-07-10']);

        $resultado = Receita::porData('2024-01-01', '2024-06-30')->get();

        $this->assertCount(1, $resultado);
        $this->assertEquals('Janeiro', $resultado->first()->nome);
    }

    /** @test */
    public function scope_por_status_sem_valor_retorna_todos(): void
    {
        $this->criar(['status' => 'ativo']);
        $this->criar(['status' => 'inativo']);

        $todos = Receita::porStatus(null)->get();

        $this->assertCount(2, $todos);
    }

    /** @test */
    public function receita_possui_fillable_correto(): void
    {
        $receita = new Receita();
        $esperado = ['nome', 'descricao', 'data_registro', 'custo', 'tipo_receita', 'status'];

        $this->assertEquals($esperado, $receita->getFillable());
    }
}
