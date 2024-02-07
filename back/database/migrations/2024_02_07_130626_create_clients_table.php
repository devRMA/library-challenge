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
        Schema::create('clients', function (Blueprint $table) {
            $table->id()
                ->comment('Identificador único');

            $table->string('name')
                ->comment('Nome do cliente');
            $table->string('cpf', 11)
                ->nullable()
                ->comment('CPF do cliente');

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
        Schema::dropIfExists('clients');
    }
};
