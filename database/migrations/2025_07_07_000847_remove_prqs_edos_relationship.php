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
        Schema::table('prqs', function (Blueprint $table) {
            $table->dropForeign(['id_edo']);
            $table->dropColumn('id_edo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prqs', function (Blueprint $table) {
            //
        });
    }
};
