<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adiciona email e timestamps aos usuários
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('email')->nullable()->after('login');
            $table->timestamps();
        });

        // Adiciona status e timestamps às receitas
        Schema::table('receitas', function (Blueprint $table) {
            $table->enum('status', ['ativo', 'inativo'])->default('ativo')->after('tipo_receita');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['email', 'created_at', 'updated_at']);
        });

        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn(['status', 'created_at', 'updated_at']);
        });
    }
};
