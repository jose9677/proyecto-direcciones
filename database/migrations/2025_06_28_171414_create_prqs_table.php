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
        Schema::create('prqs', function (Blueprint $table) {
            $table->id('id_prq');
            $table->unsignedBigInteger('id_edo');
            $table->unsignedBigInteger('id_mun');
            $table->string('parroquia', 255);
            $table->timestamps();

            $table->foreign('id_edo')->references('id_edo')->on('edos')->onUpdate('cascade');
            $table->foreign('id_mun')->references('id_mun')->on('muns')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prqs');
    }
};
