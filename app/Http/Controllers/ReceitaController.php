<?php

namespace App\Http\Controllers;

use App\Mail\ReceitaNotificacao;
use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReceitaController extends Controller
{
    // ----------------------------------------------------------------
    // Listagem com filtros
    // ----------------------------------------------------------------

    public function index(Request $request): View
    {
        $query = Receita::query()
            ->porData($request->data_inicio, $request->data_fim)
            ->porStatus($request->status)
            ->porTipo($request->tipo_receita)
            ->orderBy('data_registro', 'desc');

        $receitas = $query->paginate(10)->withQueryString();

        return view('receitas.index', compact('receitas'));
    }

    // ----------------------------------------------------------------
    // Formulário de criação
    // ----------------------------------------------------------------

    public function create(): View
    {
        return view('receitas.create');
    }

    // ----------------------------------------------------------------
    // Salvar nova receita
    // ----------------------------------------------------------------

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);

        $receita = Receita::create($dados);

        $this->enviarEmail($receita, 'criada');

        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita criada com sucesso!');
    }

    // ----------------------------------------------------------------
    // Exibir detalhes
    // ----------------------------------------------------------------

    public function show(Receita $receita): View
    {
        return view('receitas.show', compact('receita'));
    }

    // ----------------------------------------------------------------
    // Formulário de edição
    // ----------------------------------------------------------------

    public function edit(Receita $receita): View
    {
        return view('receitas.edit', compact('receita'));
    }

    // ----------------------------------------------------------------
    // Atualizar receita
    // ----------------------------------------------------------------

    public function update(Request $request, Receita $receita): RedirectResponse
    {
        $dados = $this->validar($request);

        $receita->update($dados);

        $this->enviarEmail($receita, 'atualizada');

        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita atualizada com sucesso!');
    }

    // ----------------------------------------------------------------
    // Excluir receita
    // ----------------------------------------------------------------

    public function destroy(Receita $receita): RedirectResponse
    {
        $receita->delete();

        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita excluída com sucesso!');
    }

    // ----------------------------------------------------------------
    // Exportar para PDF
    // ----------------------------------------------------------------

    public function exportarPdf(Request $request): Response
    {
        $receitas = Receita::query()
            ->porData($request->data_inicio, $request->data_fim)
            ->porStatus($request->status)
            ->porTipo($request->tipo_receita)
            ->orderBy('data_registro', 'desc')
            ->get();

        $filtros = [
            'data_inicio'  => $request->data_inicio,
            'data_fim'     => $request->data_fim,
            'status'       => $request->status,
            'tipo_receita' => $request->tipo_receita,
        ];

        $html = view('receitas.pdf', compact('receitas', 'filtros'))->render();

        return response($html, 200, [
            'Content-Type'  => 'text/html; charset=UTF-8',
            'X-PDF-Content' => 'true',
        ]);
    }

    // ----------------------------------------------------------------
    // Helpers privados
    // ----------------------------------------------------------------

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'required|string',
            'data_registro' => 'required|date',
            'custo'         => 'required|numeric|min:0',
            'tipo_receita'  => 'required|in:doce,salgada',
            'status'        => 'required|in:ativo,inativo',
        ], [
            'nome.required'          => 'O nome é obrigatório.',
            'descricao.required'     => 'A descrição é obrigatória.',
            'data_registro.required' => 'A data de registro é obrigatória.',
            'data_registro.date'     => 'Data inválida.',
            'custo.required'         => 'O custo é obrigatório.',
            'custo.numeric'          => 'O custo deve ser um número.',
            'custo.min'              => 'O custo não pode ser negativo.',
            'tipo_receita.required'  => 'O tipo de receita é obrigatório.',
            'tipo_receita.in'        => 'Tipo inválido. Use: doce ou salgada.',
            'status.required'        => 'O status é obrigatório.',
            'status.in'              => 'Status inválido. Use: ativo ou inativo.',
        ]);
    }

    private function enviarEmail(Receita $receita, string $acao): void
    {
        try {
            $usuarios = Usuario::whereNotNull('email')
                ->where('email', '!=', '')
                ->where('situacao', 'ativo')
                ->get();

            foreach ($usuarios as $usuario) {
                Mail::to($usuario->email)
                    ->send(new ReceitaNotificacao($receita, $acao));
            }
        } catch (\Throwable $e) {
            logger()->error("Falha ao enviar e-mail de receita: " . $e->getMessage());
        }
    }
}
