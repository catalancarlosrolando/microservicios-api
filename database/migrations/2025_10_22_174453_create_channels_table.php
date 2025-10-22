<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ChannelType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();

            // Información del canal
            $table->string('name');                        // Nombre del canal
            $table->text('description')->nullable();       // Descripción opcional
            $table->enum('type', ChannelType::values()); // Tipo de canal
            $table->boolean('is_active')->default(true);   // Canal activo/inactivo

            $table->timestamps();

            // Índice compuesto para filtrar por tipo y estado activo
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
