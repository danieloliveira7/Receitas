@extends('layouts.app')

@section('titulo', 'Lista de Receitas')

@section('conteudo')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:22px;">📋 Receitassssss</h2>
        <a href="{{ route('receitas.create') }}" class="btn btn-success">+ Nova Receita</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('receitas.index') }}" style="background:#f8f9fa; padding:16px; border-radius:8px; margin-bottom:24px;">
        <div style="display:flex; flex-wrap:wrap; gap:14px; align-items:flex-end;">
            <div>
                <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Data Início</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                    style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; font-size:14px;">
            </div>
            <div>
                <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Data Fim</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                    style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; font-size:14px;">
            </div>
            <div>
                <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Status</label>
                <select name="status" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; font-size:14px;">
                    <option value="">Todos</option>
                    <option value="ativo"   @selected(request('status') === 'ativo')>Ativo</option>
                    <option value="inativo" @selected(request('status') === 'inativo')>Inativo</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Tipo</label>
                <select name="tipo_receita" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; font-size:14px;">
                    <option value="">Todos</option>
                    <option value="doce"    @selected(request('tipo_receita') === 'doce')>Doce</option>
                    <option value="salgada" @selected(request('tipo_receita') === 'salgada')>Salgada</option>
                </select>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                <a href="{{ route('receitas.index') }}" class="btn btn-secondary">✕ Limpar</a>
                <a href="{{ route('receitas.pdf', request()->query()) }}" class="btn btn-warning" target="_blank">📄 Exportar PDF</a>
            </div>
        </div>
    </form>

    {{-- Tabela --}}
    @if($receitas->isEmpty())
        <p style="text-align:center; color:#999; padding:40px 0;">Nenhuma receita encontrada.</p>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f0f0f0;">
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">#</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Nome</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Tipo</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Custo</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Data Registro</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Status</th>
                    <th style="padding:10px 12px; text-align:left; border-bottom:2px solid #ddd;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receitas as $receita)
                <tr style="border-bottom:1px solid #f0f0f0; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                    <td style="padding:10px 12px; color:#999;">{{ $receita->id }}</td>
                    <td style="padding:10px 12px; font-weight:600;">{{ $receita->nome }}</td>
                    <td style="padding:10px 12px;">
                        <span style="background:{{ $receita->tipo_receita === 'doce' ? '#ffeeba' : '#d1ecf1' }}; color:{{ $receita->tipo_receita === 'doce' ? '#856404' : '#0c5460' }}; padding:2px 10px; border-radius:20px; font-size:12px;">
                            {{ ucfirst($receita->tipo_receita) }}
                        </span>
                    </td>
                    <td style="padding:10px 12px;">R$ {{ number_format($receita->custo, 2, ',', '.') }}</td>
                    <td style="padding:10px 12px;">{{ \Carbon\Carbon::parse($receita->data_registro)->format('d/m/Y') }}</td>
                    <td style="padding:10px 12px;">
                        <span style="background:{{ $receita->status === 'ativo' ? '#d4edda' : '#f8d7da' }}; color:{{ $receita->status === 'ativo' ? '#155724' : '#721c24' }}; padding:2px 10px; border-radius:20px; font-size:12px;">
                            {{ ucfirst($receita->status) }}
                        </span>
                    </td>
                    <td style="padding:10px 12px;">
                        <a href="{{ route('receitas.show', $receita) }}" class="btn btn-secondary btn-sm">Ver</a>
                        <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form method="POST" action="{{ route('receitas.destroy', $receita) }}" style="display:inline;" onsubmit="return confirm('Confirmar exclusão?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $receitas->links() }}
    </div>
    @endif
</div>
@endsection
