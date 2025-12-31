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
        Schema::dropIfExists('gantt_dependencies');
        
        Schema::create('gantt_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('taches')->onDelete('cascade');
            $table->foreignId('depends_on_task_id')->constrained('taches')->onDelete('cascade');
            $table->enum('dependency_type', ['FS', 'SS', 'FF', 'SF'])->default('FS'); // Finish-to-Start, Start-to-Start, Finish-to-Finish, Start-to-Finish
            $table->integer('lag_days')->default(0); // Délai en jours (positif ou négatif)
            $table->timestamps();
            
            // Empêcher les doublons (nom court pour MySQL)
            $table->unique(['task_id', 'depends_on_task_id', 'dependency_type'], 'gantt_deps_unique');
        });
        
        // Index pour performance
        Schema::table('gantt_dependencies', function (Blueprint $table) {
            $table->index('task_id');
            $table->index('depends_on_task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gantt_dependencies');
    }
};
