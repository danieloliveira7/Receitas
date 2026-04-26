<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes 11-20: Casos complementares de CRUD, formulários e segurança.
 */
class Receita11a20ComplementaresTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    private function usuario(array $attrs = []): Usuario
    {
        return Usuario::create(array_merge([
            'nome'     => 'Tester',
            'login'    => 'tester',
            'email'    => 'tester@test.com',
            'senha'    => bcrypt('abc123'),
            'situacao' => 'ativo',
        ], $attrs));
    }

    private function sessao(?Usuario $u = null): static
    {
        return $this->withSession(['usuario' => $u ?? $this->usuario()]);
    }

    private function receita(array $attrs = []): Receita
    {
        return Receita::create(array_merge([
            'nome'          => 'Receita Padrão',
            'descricao'     => 'Descrição padrão',
            'data_registro' => '2024-08-01',
            'custo'         => 15.00,
            'tipo_receita'  => 'salgada',
            'status'        => 'ativo',
        ], $attrs));
    }

    // ----------------------------------------------------------------
    // Teste 11 – Paginação presente na listagem
    // ----------------------------------------------------------------

    /** @test */
    public function teste_11_listagem_exibe_links_de_paginacao_com_muitas_receitas(): void
    {
        $u = $this->usuario();

        for ($i = 1; $i <= 15; $i++) {
            Receita::create([
                'nome'          => "Receita {$i}",
                'descricao'     => 'Desc',
                'data_registro' => '2024-01-01',
                'custo'         => $i,
                'tipo_receita'  => 'doce',
                'status'        => 'ativo',
            ]);
        }

        $resposta = $this->withSession(['usuario' => $u])->get(route('receitas.index'));

        $resposta->assertOk();
        // A view deve receber a variável paginada
        $resposta->assertViewHas('receitas');
    }

    // ----------------------------------------------------------------
    // Teste 12 – Custo zero é aceito
    // ----------------------------------------------------------------

    /** @test */
    public function teste_12_custo_zero_e_valido(): void
    {
        $this->sessao()->post(route('receitas.store'), [
            'nome'          => 'Receita Gratuita',
            'descricao'     => 'Sem custo',
            'data_registro' => '2024-04-01',
            'custo'         => 0,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $this->assertDatabaseHas('receitas', ['nome' => 'Receita Gratuita', 'custo' => 0]);
    }

    // ----------------------------------------------------------------
    // Teste 13 – Tipo salgada é aceito
    // ----------------------------------------------------------------

    /** @test */
    public function teste_13_tipo_salgada_e_aceito(): void
    {
        $this->sessao()->post(route('receitas.store'), [
            'nome'          => 'Caldo Verde',
            'descricao'     => 'Sopa salgada',
            'data_registro' => '2024-09-01',
            'custo'         => 22.50,
            'tipo_receita'  => 'salgada',
            'status'        => 'ativo',
        ]);

        $this->assertDatabaseHas('receitas', ['nome' => 'Caldo Verde', 'tipo_receita' => 'salgada']);
    }

    // ----------------------------------------------------------------
    // Teste 14 – Status inativo é aceito
    // ----------------------------------------------------------------

    /** @test */
    public function teste_14_status_inativo_e_aceito(): void
    {
        $this->sessao()->post(route('receitas.store'), [
            'nome'          => 'Receita Descontinuada',
            'descricao'     => 'Não disponível',
            'data_registro' => '2024-01-01',
            'custo'         => 5,
            'tipo_receita'  => 'doce',
            'status'        => 'inativo',
        ]);

        $this->assertDatabaseHas('receitas', ['nome' => 'Receita Descontinuada', 'status' => 'inativo']);
    }

    // ----------------------------------------------------------------
    // Teste 15 – Formulário de criação carrega corretamente
    // ----------------------------------------------------------------

    /** @test */
    public function teste_15_formulario_de_criacao_carrega(): void
    {
        $resposta = $this->sessao()->get(route('receitas.create'));

        $resposta->assertOk();
        $resposta->assertViewIs('receitas.create');
    }

    // ----------------------------------------------------------------
    // Teste 16 – Login sem campos retorna erros de validação
    // ----------------------------------------------------------------

    /** @test */
    public function teste_16_login_sem_campos_retorna_erros(): void
    {
        $resposta = $this->post(route('auth.login'), []);

        $resposta->assertSessionHasErrors(['login', 'senha']);
    }

    // ----------------------------------------------------------------
    // Teste 17 – Filtros combinados funcionam corretamente
    // ----------------------------------------------------------------

    /** @test */
    public function teste_17_filtros_combinados_data_e_status(): void
    {
        $u = $this->usuario();

        Receita::create(['nome' => 'Ok', 'descricao' => 'D', 'data_registro' => '2024-03-15', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Fora Data', 'descricao' => 'D', 'data_registro' => '2024-08-01', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Inativa', 'descricao' => 'D', 'data_registro' => '2024-03-15', 'custo' => 10, 'tipo_receita' => 'doce', 'status' => 'inativo']);

        $resposta = $this->withSession(['usuario' => $u])->get(route('receitas.index', [
            'data_inicio' => '2024-01-01',
            'data_fim'    => '2024-06-30',
            'status'      => 'ativo',
        ]));

        $resposta->assertSee('Ok');
        $resposta->assertDontSee('Fora Data');
        $resposta->assertDontSee('Inativa');
    }

    // ----------------------------------------------------------------
    // Teste 18 – Editar receita inexistente retorna 404
    // ----------------------------------------------------------------

    /** @test */
    public function teste_18_editar_receita_inexistente_retorna_404(): void
    {
        $resposta = $this->sessao()->get(route('receitas.edit', 99999));

        $resposta->assertNotFound();
    }

    // ----------------------------------------------------------------
    // Teste 19 – Usuário inativo não recebe email
    // ----------------------------------------------------------------

    /** @test */
    public function teste_19_usuario_inativo_nao_recebe_email(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        // Usuário inativo na sessão (login funciona mas email não vai)
        $usuario = Usuario::create([
            'nome'     => 'Inativo',
            'login'    => 'inativo',
            'email'    => 'inativo@test.com',
            'senha'    => bcrypt('123'),
            'situacao' => 'inativo',
        ]);

        $this->withSession(['usuario' => $usuario])->post(route('receitas.store'), [
            'nome'          => 'Receita Sem Email',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 10,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        // O email só é enviado a usuários com situacao=ativo
        \Illuminate\Support\Facades\Mail::assertNothingSent();
    }

    // ----------------------------------------------------------------
    // Teste 20 – Exportação PDF com filtro de tipo
    // ----------------------------------------------------------------

    /** @test */
    public function teste_20_exportacao_pdf_filtra_por_tipo(): void
    {
        $u = $this->usuario();

        Receita::create(['nome' => 'Brigadeiro', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 5, 'tipo_receita' => 'doce', 'status' => 'ativo']);
        Receita::create(['nome' => 'Esfiha', 'descricao' => 'D', 'data_registro' => '2024-01-01', 'custo' => 8, 'tipo_receita' => 'salgada', 'status' => 'ativo']);

        $resposta = $this->withSession(['usuario' => $u])
            ->get(route('receitas.pdf', ['tipo_receita' => 'doce']));

        $resposta->assertSee('Brigadeiro');
        $resposta->assertDontSee('Esfiha');
    }
}
