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
        Schema::create('gantt_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('taches')->onDelete('cascade');
            $table->string('action', 50); // create, update, delete, reschedule, dependency_add, dependency_remove
            $table->string('field_name')->nullable(); // Nom du champ modifié (date_debut_prevue, etc.)
            $table->json('old_value')->nullable(); // Ancienne valeur
            $table->json('new_value')->nullable(); // Nouvelle valeur
            $table->boolean('requires_approval')->default(false); // Nécessite approbation
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
            
            // Index pour performance
            $table->index('task_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('requires_approval');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gantt_audit_logs');
    }
};
