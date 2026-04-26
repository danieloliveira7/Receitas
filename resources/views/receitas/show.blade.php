@extends('layouts.app')

@section('titulo', $receita->nome)

@section('conteudo')
<div class="card" style="max-width:700px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('receitas.index') }}" class="btn btn-secondary btn-sm">← Voltar</a>
            <h2 style="font-size:20px;">🍽️ {{ $receita->nome }}</h2>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
            <form method="POST" action="{{ route('receitas.destroy', $receita) }}" onsubmit="return confirm('Confirmar exclusão?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">🗑️ Excluir</button>
            </form>
        </div>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        @php
            $linhas = [
                'ID'             => $receita->id,
                'Nome'           => $receita->nome,
                'Descrição'      => $receita->descricao,
                'Tipo'           => ucfirst($receita->tipo_receita),
                'Custo'          => 'R$ ' . number_format($receita->custo, 2, ',', '.'),
                'Data Registro'  => \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y'),
                'Status'         => ucfirst($receita->status),
                'Criado em'      => $receita->created_at ? $receita->created_at->format('d/m/Y H:i') : '—',
                'Atualizado em'  => $receita->updated_at ? $receita->updated_at->format('d/m/Y H:i') : '—',
            ];
        @endphp
        @foreach($linhas as $label => $valor)
        <tr style="border-bottom:1px solid #f0f0f0;">
            <th style="padding:12px 16px; text-align:left; color:#888; font-size:12px; width:160px; background:#f8f9fa;">{{ $label }}</th>
            <td style="padding:12px 16px;">
                @if($label === 'Status')
                    <span style="background:{{ $receita->status === 'ativo' ? '#d4edda' : '#f8d7da' }}; color:{{ $receita->status === 'ativo' ? '#155724' : '#721c24' }}; padding:2px 12px; border-radius:20px; font-size:12px;">
                        {{ $valor }}
                    </span>
                @else
                    {{ $valor }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
