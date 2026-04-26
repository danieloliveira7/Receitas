<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'senha' => 'required|string',
        ], [
            'login.required' => 'O login é obrigatório.',
            'senha.required' => 'A senha é obrigatória.',
        ]);

        $user = Usuario::where('login', $request->login)->first();

        if ($user && Hash::check($request->senha, $user->senha)) {
            session(['usuario' => $user]);
            return redirect()->route('receitas.index');
        }

        return back()->with('erro', 'Login ou senha inválidos.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('usuario');
        return redirect()->route('login')->with('sucesso', 'Você saiu com sucesso.');
    }
}
