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
        Schema::create('books', function (Blueprint $table) {
            $table->id()
                ->comment('Identificador único do livro');

            $table->string('nome')
                ->comment('Nome do livro');

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
        Schema::dropIfExists('books');
    }
};
