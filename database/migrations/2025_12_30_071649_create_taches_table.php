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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_prioritaire_id')->constrained('actions_prioritaires')->onDelete('cascade');
            $table->foreignId('tache_parent_id')->nullable()->constrained('taches')->onDelete('cascade');
            $table->string('code', 32)->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('statut', 20)->default('brouillon');
            $table->string('priorite', 20)->default('normale');
            $table->string('criticite', 20)->default('normal');
            $table->dateTime('date_debut_prevue')->nullable();
            $table->dateTime('date_fin_prevue')->nullable();
            $table->dateTime('date_debut_reelle')->nullable();
            $table->dateTime('date_fin_reelle')->nullable();
            $table->integer('pourcentage_avancement')->default(0);
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('bloque')->default(false);
            $table->text('raison_blocage')->nullable();
            $table->boolean('est_jalon')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
