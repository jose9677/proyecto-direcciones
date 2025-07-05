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
        Schema::create('muns', function (Blueprint $table) {
            $table->id('id_mun');
            $table->unsignedBigInteger('id_edo');
            $table->string('municipio', 255);
            $table->timestamps();

            $table->foreign('id_edo')->references('id_edo')->on('edos')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muns');
    }
};
