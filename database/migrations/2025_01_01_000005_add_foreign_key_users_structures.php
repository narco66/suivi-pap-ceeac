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
        // Ajouter la contrainte de clé étrangère après que les deux tables existent
        if (Schema::hasTable('users') && Schema::hasTable('structures')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'structure_id') && !$this->foreignKeyExists('users', 'users_structure_id_foreign')) {
                    $table->foreign('structure_id')->references('id')->on('structures')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['structure_id']);
            } catch (\Exception $e) {
                // La contrainte n'existe peut-être pas
            }
        });
    }

    /**
     * Vérifier si une clé étrangère existe
     */
    protected function foreignKeyExists(string $table, string $keyName): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count 
             FROM information_schema.KEY_COLUMN_USAGE 
             WHERE TABLE_SCHEMA = ? 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?",
            [$database, $table, $keyName]
        );
        
        return $result[0]->count > 0;
    }
};



