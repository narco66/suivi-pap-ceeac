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
        Schema::table('rapports', function (Blueprint $table) {
            $table->enum('scope_level', ['GLOBAL', 'SG', 'COMMISSAIRE'])->nullable()->after('type');
            $table->string('checksum')->nullable()->after('fichier_genere');
            $table->index('scope_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            //
        });
    }
};
