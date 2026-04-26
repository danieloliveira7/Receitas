<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Receitas</title>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #222; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #2c3e50; margin-bottom: 4px; }
        .header p { color: #777; font-size: 12px; }
        .filtros { background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 12px; }
        .filtros span { margin-right: 18px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #2c3e50; color: #fff; }
        thead th { padding: 10px 10px; text-align: left; font-size: 12px; font-weight: 600; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        tbody td { padding: 9px 10px; border-bottom: 1px solid #eee; }
        .badge { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .ativo { background: #d4edda; color: #155724; }
        .inativo { background: #f8d7da; color: #721c24; }
        .doce { background: #fff3cd; color: #856404; }
        .salgada { background: #d1ecf1; color: #0c5460; }
        .total { margin-top: 16px; text-align: right; font-size: 13px; font-weight: bold; color: #555; }
        .footer { margin-top: 28px; border-top: 1px solid #ddd; padding-top: 10px; text-align: center; color: #aaa; font-size: 11px; }
        .btn-print { margin-bottom: 16px; padding: 8px 20px; background: #2980b9; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">🖨️ Imprimir / Salvar como PDF</button>

    <div class="header">
        <h1>📋 Relatório de Receitas</h1>
        <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(array_filter($filtros))
    <div class="filtros">
        <strong>Filtros aplicados:</strong>
        @if($filtros['data_inicio'])  <span>Data início: {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }}</span> @endif
        @if($filtros['data_fim'])     <span>Data fim: {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}</span> @endif
        @if($filtros['status'])       <span>Status: {{ ucfirst($filtros['status']) }}</span> @endif
        @if($filtros['tipo_receita']) <span>Tipo: {{ ucfirst($filtros['tipo_receita']) }}</span> @endif
    </div>
    @endif

    @if($receitas->isEmpty())
        <p style="text-align:center; color:#999; padding:30px;">Nenhuma receita encontrada para os filtros selecionados.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Custo</th>
                <th>Data Registro</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receitas as $receita)
            <tr>
                <td style="color:#999;">{{ $receita->id }}</td>
                <td><strong>{{ $receita->nome }}</strong></td>
                <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Str::limit($receita->descricao, 60) }}</td>
                <td><span class="badge {{ $receita->tipo_receita }}">{{ ucfirst($receita->tipo_receita) }}</span></td>
                <td>R$ {{ number_format($receita->custo, 2, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y') }}</td>
                <td><span class="badge {{ $receita->status }}">{{ ucfirst($receita->status) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: {{ $receitas->count() }} receita(s) |
        Custo total: R$ {{ number_format($receitas->sum('custo'), 2, ',', '.') }}
    </div>
    @endif

    <div class="footer">
        Sistema de Receitas — Relatório gerado automaticamente
    </div>
</body>
</html>
