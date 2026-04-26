<style>
.form-group { margin-bottom: 18px; }
.form-group label { display:block; font-size:13px; font-weight:600; color:#555; margin-bottom:6px; }
.form-group input, .form-group textarea, .form-group select {
    width:100%; border:1px solid #ddd; border-radius:6px;
    padding:9px 12px; font-size:14px; transition:border-color .2s;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    outline:none; border-color:#2980b9; box-shadow:0 0 0 3px rgba(41,128,185,.1);
}
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
</style>

<div class="form-group">
    <label for="nome">Nome da Receita *</label>
    <input type="text" id="nome" name="nome" value="{{ old('nome', $receita->nome ?? '') }}" placeholder="Ex: Bolo de Chocolate" required>
</div>

<div class="form-group">
    <label for="descricao">Descrição *</label>
    <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva os ingredientes e o modo de preparo..." required>{{ old('descricao', $receita->descricao ?? '') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="data_registro">Data de Registro *</label>
        <input type="date" id="data_registro" name="data_registro"
            value="{{ old('data_registro', isset($receita) ? \Carbon\Carbon::parse($receita->data_registro)->format('Y-m-d') : '') }}" required>
    </div>
    <div class="form-group">
        <label for="custo">Custo (R$) *</label>
        <input type="number" id="custo" name="custo" step="0.01" min="0"
            value="{{ old('custo', $receita->custo ?? '') }}" placeholder="0,00" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="tipo_receita">Tipo de Receita *</label>
        <select id="tipo_receita" name="tipo_receita" required>
            <option value="">Selecione...</option>
            <option value="doce"    @selected(old('tipo_receita', $receita->tipo_receita ?? '') === 'doce')>🍰 Doce</option>
            <option value="salgada" @selected(old('tipo_receita', $receita->tipo_receita ?? '') === 'salgada')>🥗 Salgada</option>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
            <option value="">Selecione...</option>
            <option value="ativo"   @selected(old('status', $receita->status ?? 'ativo') === 'ativo')>✅ Ativo</option>
            <option value="inativo" @selected(old('status', $receita->status ?? '') === 'inativo')>❌ Inativo</option>
        </select>
    </div>
</div>
