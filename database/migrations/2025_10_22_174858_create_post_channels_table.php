<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_channels', function (Blueprint $table) {
            //$table->id(); como es una tabla pivot no necesito un id incremental. para ello esta la clave primaria compuesta. 

            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Un post solo puede estar una vez en cada canal
            $table->primary(['post_id', 'channel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_channels');
    }
};
