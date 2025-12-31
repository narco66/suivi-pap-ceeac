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
        Schema::create('actions_prioritaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('objectif_id')->constrained('objectifs')->onDelete('cascade');
            $table->string('code', 32)->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('type', 20)->default('technique');
            $table->foreignId('direction_technique_id')->nullable()->constrained('directions_techniques')->onDelete('set null');
            $table->foreignId('direction_appui_id')->nullable()->constrained('directions_appui')->onDelete('set null');
            $table->string('statut', 20)->default('brouillon');
            $table->string('priorite', 20)->default('normale');
            $table->string('criticite', 20)->default('normal');
            $table->dateTime('date_debut_prevue')->nullable();
            $table->dateTime('date_fin_prevue')->nullable();
            $table->dateTime('date_debut_reelle')->nullable();
            $table->dateTime('date_fin_reelle')->nullable();
            $table->integer('pourcentage_avancement')->default(0);
            $table->boolean('bloque')->default(false);
            $table->text('raison_blocage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions_prioritaires');
    }
};
