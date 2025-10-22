<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            // Información del archivo
            $table->string('name');                        // Nombre para mostrar
            $table->string('file_name');                   // Nombre real del archivo
            $table->string('file_path');                   // Ruta donde se guardó
            $table->string('mime_type');                   // Tipo MIME (image/jpeg, etc.)
            $table->unsignedBigInteger('size');            // Tamaño en bytes
            $table->string('disk')->default('public');     // Disco de almacenamiento
            $table->text('description')->nullable();       // Descripción opcional

            // Estadísticas
            $table->integer('download_count')->default(0); // Contador de descargas

            // Relación con el usuario que subió el archivo
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
