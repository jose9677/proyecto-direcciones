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
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unit_type');
            $table->unsignedBigInteger('id_property_type');
            $table->unsignedBigInteger('id_residential_complex');
            $table->integer('tower');
            $table->boolean('is_resi_complex');

            $table->foreign('id_unit_type')->references('id_unit_type')->on('unit_types')->onUpdate('cascade');
            $table->foreign('id_property_type')->references('id_property_type')->on('property_types')->onUpdate('cascade');
            $table->foreign('id_residential_complex')->references('id_residential_complex')->on('residential_complexes')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
              $table->dropForeign(['id_property_type']); // Usa un array si el nombre es genérico
              $table->dropForeign(['id_residential_complex']); // Usa un array si el nombre es genérico
              $table->dropForeign(['id_unit_type']); // Usa un array si el nombre es genérico

            // O si Laravel generó un nombre específico, puedes usar:
            // $table->dropForeign('products_category_id_foreign'); // Reemplaza con el nombre real si lo conoces

            // Eliminar la columna category_id
            $table->dropColumn('id_unit_type');
            $table->dropColumn('id_property_type');
            $table->dropColumn('id_residential_complex');
            $table->dropColumn('tower');
            $table->dropColumn('is_resi_complex');
        });
    }
};
