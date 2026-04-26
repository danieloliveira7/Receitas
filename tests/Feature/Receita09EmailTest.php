<?php

namespace Tests\Feature;

use App\Mail\ReceitaNotificacao;
use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Receita09EmailTest extends TestCase
{
    use RefreshDatabase;

    private function fazerLogin(): void
    {
        $usuario = Usuario::create([
            'nome'     => 'Teste',
            'login'    => 'teste',
            'email'    => 'teste@teste.com',
            'senha'    => bcrypt('123456'),
            'situacao' => 'ativo',
        ]);
        $this->withSession(['usuario' => $usuario]);
    }

    /** @test */
    public function email_e_enviado_ao_criar_receita(): void
    {
        Mail::fake();
        $this->fazerLogin();

        $this->post(route('receitas.store'), [
            'nome'          => 'Pão de Mel',
            'descricao'     => 'Delicioso pão de mel com cobertura de chocolate',
            'data_registro' => '2024-02-14',
            'custo'         => 12.00,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        Mail::assertSent(ReceitaNotificacao::class);
    }

    /** @test */
    public function email_e_enviado_ao_atualizar_receita(): void
    {
        Mail::fake();
        $this->fazerLogin();

        $receita = Receita::create([
            'nome'          => 'Torta',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 20,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $this->put(route('receitas.update', $receita), [
            'nome'          => 'Torta Editada',
            'descricao'     => 'Nova desc',
            'data_registro' => '2024-01-01',
            'custo'         => 25,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        Mail::assertSent(ReceitaNotificacao::class);
    }

    /** @test */
    public function mailable_tem_assunto_correto_para_criacao(): void
    {
        $receita = new Receita([
            'nome'          => 'Brigadeiro',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 5,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $mail = new ReceitaNotificacao($receita, 'criada');

        $this->assertStringContainsString('Nova Receita Cadastrada', $mail->envelope()->subject);
    }

    /** @test */
    public function mailable_tem_assunto_correto_para_atualizacao(): void
    {
        $receita = new Receita([
            'nome'          => 'Brigadeiro',
            'descricao'     => 'Desc',
            'data_registro' => '2024-01-01',
            'custo'         => 5,
            'tipo_receita'  => 'doce',
            'status'        => 'ativo',
        ]);

        $mail = new ReceitaNotificacao($receita, 'atualizada');

        $this->assertStringContainsString('Receita Atualizada', $mail->envelope()->subject);
    }
}
