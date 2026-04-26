<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema de Receitas</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #2c3e50, #3498db); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.18); padding: 40px 36px; width: 100%; max-width: 380px; }
        .card h2 { text-align: center; font-size: 22px; color: #2c3e50; margin-bottom: 28px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        .form-group input { width: 100%; border: 1px solid #ddd; border-radius: 6px; padding: 10px 12px; font-size: 14px; transition: border-color .2s; }
        .form-group input:focus { outline: none; border-color: #3498db; box-shadow: 0 0 0 3px rgba(52,152,219,.1); }
        .btn { width: 100%; padding: 11px; background: #2980b9; color: #fff; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; margin-top: 8px; transition: background .2s; }
        .btn:hover { background: #1a6fa0; }
        .erro { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🍽️ Sistema de Receitas</h2>

        @if(session('erro'))
            <div class="erro">{{ session('erro') }}</div>
        @endif

        @if(session('sucesso'))
            <div style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:6px; padding:10px 14px; font-size:13px; margin-bottom:16px;">{{ session('sucesso') }}</div>
        @endif

        <form method="POST" action="{{ route('auth.login') }}">
            @csrf
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Seu login" autofocus required>
                @error('login') <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
                @error('senha') <span style="color:#e74c3c; font-size:12px;">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>
