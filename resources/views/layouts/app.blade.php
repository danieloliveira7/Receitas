<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Receitas') — Sistema de Receitas</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #333; }
        .navbar {
            background: #2c3e50; color: #fff; display: flex;
            align-items: center; justify-content: space-between;
            padding: 0 32px; height: 56px;
        }
        .navbar a { color: #fff; text-decoration: none; font-weight: 600; font-size: 18px; }
        .navbar-right { display: flex; gap: 16px; align-items: center; }
        .navbar-right a, .navbar-right form button {
            background: none; border: none; color: #ecf0f1;
            font-size: 14px; cursor: pointer; text-decoration: none;
        }
        .navbar-right form button:hover, .navbar-right a:hover { color: #e74c3c; }
        .container { width: 100%; margin: 32px 0; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); padding: 28px; }
        .alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 8px 18px; border-radius: 6px; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: opacity .2s; }
        .btn:hover { opacity: .85; }
        .btn-primary { background: #2980b9; color: #fff; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-warning { background: #f39c12; color: #fff; }
        .btn-danger  { background: #e74c3c; color: #fff; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        .btn-sm { padding: 4px 12px; font-size: 12px; }

        /* Paginação do Laravel (Tailwind) */
        nav[role="navigation"] { margin-top: 16px; }
        nav[role="navigation"] svg { width: 18px; height: 18px; vertical-align: middle; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 6px 12px; margin: 0 2px; border: 1px solid #ddd; border-radius: 5px;
            color: #2980b9; text-decoration: none; font-size: 13px; min-width: 32px;
        }
        nav[role="navigation"] span[aria-current="page"] span {
            background: #2980b9; color: #fff; border-color: #2980b9; border: none; padding: 0;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ route('receitas.index') }}">🍽️ Receitas</a>
    <div class="navbar-right">
        @if(session('usuario'))
            <span style="color:#bdc3c7">Olá, {{ session('usuario')->nome }}</span>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit">Sair</button>
            </form>
        @endif
    </div>
</nav>

<div class="container">
    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif
    @if(session('erro'))
        <div class="alert alert-danger">{{ session('erro') }}</div>
    @endif

    @yield('conteudo')
</div>
</body>
</html>