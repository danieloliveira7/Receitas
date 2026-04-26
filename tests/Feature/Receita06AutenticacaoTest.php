<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Receita06AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_nao_autenticado_e_redirecionado(): void
    {
        $resposta = $this->get(route('receitas.index'));

        $resposta->assertRedirect(route('login'));
    }

    /** @test */
    public function login_valido_redireciona_para_receitas(): void
    {
        Usuario::create([
            'nome'     => 'Admin',
            'login'    => 'admin',
            'senha'    => Hash::make('senha123'),
            'situacao' => 'ativo',
        ]);

        $resposta = $this->post(route('auth.login'), [
            'login' => 'admin',
            'senha' => 'senha123',
        ]);

        $resposta->assertRedirect(route('receitas.index'));
        $this->assertTrue(session()->has('usuario'));
    }

    /** @test */
    public function login_invalido_retorna_erro(): void
    {
        Usuario::create([
            'nome'     => 'Admin',
            'login'    => 'admin',
            'senha'    => Hash::make('senha123'),
            'situacao' => 'ativo',
        ]);

        $resposta = $this->post(route('auth.login'), [
            'login' => 'admin',
            'senha' => 'errada',
        ]);

        $resposta->assertSessionHas('erro');
        $this->assertFalse(session()->has('usuario'));
    }

    /** @test */
    public function logout_remove_sessao(): void
    {
        $usuario = Usuario::create([
            'nome'     => 'Admin',
            'login'    => 'admin',
            'senha'    => Hash::make('senha123'),
            'situacao' => 'ativo',
        ]);

        $resposta = $this->withSession(['usuario' => $usuario])
            ->post(route('auth.logout'));

        $resposta->assertRedirect(route('login'));
        $this->assertFalse(session()->has('usuario'));
    }

    /** @test */
    public function rota_create_exige_autenticacao(): void
    {
        $resposta = $this->get(route('receitas.create'));
        $resposta->assertRedirect(route('login'));
    }
}
