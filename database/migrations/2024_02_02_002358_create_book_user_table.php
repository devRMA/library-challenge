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
        Schema::create('book_user', function (Blueprint $table) {
            $table->id()
                ->comment('Identificador único do aluguel');

            $table->foreignId('livro_id')
                ->comment('Id do livro que está sendo alugado')
                ->constrained('books');
            $table->foreignId('usuario_id')
                ->comment('Usuário que está alugando o livro')
                ->constrained('users');
            $table->dateTime('dt_aluguel_ini')
                ->nullable()
                ->comment('Data de início do aluguel do livro');
            $table->dateTime('dt_aluguel_fim')
                ->nullable()
                ->comment('Data de fim do aluguel do livro');

            $table->dateTime('dt_inclusao')
                ->nullable()
                ->comment('Data de criação do livro');
            $table->dateTime('dt_alteracao')
                ->nullable()
                ->comment('Data da última modificação no livro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_user');
    }
};
