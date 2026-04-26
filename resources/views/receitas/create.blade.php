@extends('layouts.app')

@section('titulo', 'Nova Receita')

@section('conteudo')
<div class="card" style="max-width:680px; margin:0 auto;">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
        <a href="{{ route('receitas.index') }}" class="btn btn-secondary btn-sm">← Voltar</a>
        <h2 style="font-size:20px;">✨ Nova Receita</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:16px;">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('receitas.store') }}">
        @csrf
        @include('receitas._form')
        <div style="margin-top:24px; display:flex; gap:10px;">
            <button type="submit" class="btn btn-success">💾 Salvar Receita</button>
            <a href="{{ route('receitas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
