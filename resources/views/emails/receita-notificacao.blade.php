<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Notificação de Receita</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        h1 { color: #e67e22; font-size: 22px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; color: #888; font-size: 12px; padding: 8px 0; border-bottom: 1px solid #eee; }
        td { padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 15px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-ativo { background: #d4edda; color: #155724; }
        .badge-inativo { background: #f8d7da; color: #721c24; }
        .footer { margin-top: 32px; font-size: 12px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            @if($acao === 'criada')
                🍽️ Nova Receita Cadastrada
            @else
                ✏️ Receita Atualizada
            @endif
        </h1>

        <p>Olá! A receita <strong>{{ $receita->nome }}</strong> foi <strong>{{ $acao }}</strong> com sucesso.</p>

        <table>
            <tr>
                <th>Campo</th>
                <th>Valor</th>
            </tr>
            <tr>
                <td>Nome</td>
                <td>{{ $receita->nome }}</td>
            </tr>
            <tr>
                <td>Descrição</td>
                <td>{{ $receita->descricao }}</td>
            </tr>
            <tr>
                <td>Tipo</td>
                <td>{{ ucfirst($receita->tipo_receita) }}</td>
            </tr>
            <tr>
                <td>Custo</td>
                <td>R$ {{ number_format($receita->custo, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Data de Registro</td>
                <td>{{ \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span class="badge badge-{{ $receita->status }}">
                        {{ ucfirst($receita->status) }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="footer">
            Este é um e-mail automático do sistema de Receitas. Não responda este e-mail.
        </div>
    </div>
</body>
</html>
