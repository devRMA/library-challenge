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
        Schema::create('book_client', function (Blueprint $table) {
            $table->id()
                ->comment('Identificador único');

            $table->foreignId('book_id')
                ->comment('Id do livro que está sendo alugado')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('client_id')
                ->comment('Cliente que está alugando o livro')
                ->constrained()
                ->onDelete('cascade');

            $table->dateTime('rent_started_at')
                ->comment('Data de início do aluguel do livro');
            $table->dateTime('rent_ended_at')
                ->nullable()
                ->comment('Data de fim do aluguel do livro');

            $table->dateTime('created_at')
                ->nullable()
                ->comment('Data de criação');
            $table->dateTime('updated_at')
                ->nullable()
                ->comment('Data da última modificação');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_client');
    }
};
