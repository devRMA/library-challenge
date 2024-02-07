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
                ->comment('Identificador único');

            $table->string('name')
                ->comment('Nome do livro');

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
        Schema::dropIfExists('books');
    }
};
