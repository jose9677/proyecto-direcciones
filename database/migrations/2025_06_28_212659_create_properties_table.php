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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('id_property');
            $table->string('description', 255);
            $table->unsignedBigInteger('id_urb');
            $table->unsignedBigInteger('id_block');
            $table->unsignedBigInteger('id_street');
            $table->text('observations')->nullable();
            $table->boolean('verified');
            $table->boolean('active');
            $table->timestamps();

            $table->foreign('id_urb')->references('id_urb')->on('urbanizations')->onUpdate('cascade');
            $table->foreign('id_block')->references('id_block')->on('blocks')->onUpdate('cascade');
            $table->foreign('id_street')->references('id_street')->on('streets')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
