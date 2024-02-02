<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()
                ->comment('Identificador único do usuário');

            $table->string('nome')
                ->comment('Nome do usuário');
            $table->string('cpf')
                ->unique()
                ->comment('CPF do usuário');

            $table->dateTime('dt_inclusao')
                ->nullable()
                ->comment('Data de criação do usuário');
            $table->dateTime('dt_alteracao')
                ->nullable()
                ->comment('Data da última modificação no usuário');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
