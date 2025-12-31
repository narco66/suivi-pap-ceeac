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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_prioritaire_id')->constrained('actions_prioritaires')->onDelete('cascade');
            $table->string('code', 32)->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('unite', 50)->nullable();
            $table->decimal('valeur_cible', 15, 2)->default(0);
            $table->decimal('valeur_realisee', 15, 2)->default(0);
            $table->decimal('valeur_ecart', 15, 2)->default(0);
            $table->decimal('pourcentage_realisation', 5, 2)->default(0);
            $table->dateTime('date_mesure')->nullable();
            $table->string('statut', 20)->default('en_cours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
