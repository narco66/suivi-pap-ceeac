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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('titre');
            $table->text('message');
            $table->string('criticite', 20)->default('normal');
            $table->string('statut', 20)->default('ouverte');
            $table->foreignId('tache_id')->nullable()->constrained('taches')->onDelete('cascade');
            $table->foreignId('action_prioritaire_id')->nullable()->constrained('actions_prioritaires')->onDelete('cascade');
            $table->string('niveau_escalade', 20)->nullable();
            $table->foreignId('cree_par_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assignee_a_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_creation')->nullable();
            $table->dateTime('date_resolution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
