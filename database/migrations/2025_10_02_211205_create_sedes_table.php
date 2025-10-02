<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('descripcion')->nullable();
            $table->string('pais', 100);
            $table->string('departamento', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100);
            $table->string('direccion', 100)->nullable();
            $table->string('referencia', 255)->nullable();
            $table->string('nota', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
