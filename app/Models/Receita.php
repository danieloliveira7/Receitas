<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Receita extends Model
{
    protected $table = 'receitas';

    protected $fillable = [
        'nome',
        'descricao',
        'data_registro',
        'custo',
        'tipo_receita',
        'status',
    ];

    public $timestamps = true;

    // ----------------------------------------------------------------
    // Scopes de filtro
    // ----------------------------------------------------------------

    public function scopePorData(Builder $query, ?string $dataInicio, ?string $dataFim): Builder
    {
        if ($dataInicio) {
            $query->where('data_registro', '>=', $dataInicio);
        }
        if ($dataFim) {
            $query->where('data_registro', '<=', $dataFim);
        }
        return $query;
    }

    public function scopePorStatus(Builder $query, ?string $status): Builder
    {
        if ($status && in_array($status, ['ativo', 'inativo'])) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopePorTipo(Builder $query, ?string $tipo): Builder
    {
        if ($tipo && in_array($tipo, ['doce', 'salgada'])) {
            $query->where('tipo_receita', $tipo);
        }
        return $query;
    }
}