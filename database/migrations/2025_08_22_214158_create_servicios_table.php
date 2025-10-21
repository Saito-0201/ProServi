<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade');
            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->enum('tipo_precio', ['fijo', 'cotizacion', 'variable' , 'diario', 'por_servicio'])->default('fijo');
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->integer('visitas')->default(0);
            $table->dateTime('fecha_publicacion')->default(now());
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->text('direccion');
            $table->string('ciudad', 100);
            $table->string('provincia', 100);
            $table->string('pais', 100)->default('Bolivia');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->decimal('calificacion_promedio', 3, 2)->default(0); // ej: 4.75
            $table->integer('total_calificaciones')->default(0);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
