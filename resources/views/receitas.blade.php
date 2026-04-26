</div>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<h2 style="text-align:center;">Lista de Receitas</h2>

<table>
    <tr>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Tipo</th>
        <th>Custo</th>
    </tr>

    @foreach($receitas as $r)
    <tr>
        <td>{{ $r->nome }}</td>
        <td>{{ $r->descricao }}</td>
        <td>{{ $r->tipo_receita }}</td>
        <td>{{ $r->custo }}</td>
    </tr>
    @endforeach
</table>