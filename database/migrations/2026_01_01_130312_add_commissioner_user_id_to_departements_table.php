<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cette migration ajoute la relation 1-1 entre Département et Commissaire (User).
     * Un département est dirigé par UN seul commissaire (contrainte UNIQUE).
     */
    public function up(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Ajouter la colonne commissioner_user_id avec contrainte UNIQUE
            // Cela garantit qu'un département ne peut avoir qu'un seul commissaire
            // et qu'un user ne peut être commissaire que d'un seul département
            $table->foreignId('commissioner_user_id')
                ->nullable()
                ->after('actif')
                ->constrained('users')
                ->onDelete('set null');
            
            // Contrainte UNIQUE pour garantir la relation 1-1
            // Un user ne peut être commissaire que d'un seul département
            $table->unique('commissioner_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            $table->dropForeign(['commissioner_user_id']);
            $table->dropUnique(['commissioner_user_id']);
            $table->dropColumn('commissioner_user_id');
        });
    }
};
