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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Code unique du rapport
            $table->string('titre'); // Titre du rapport
            $table->text('description')->nullable(); // Description du rapport
            $table->enum('type', ['papa', 'objectif', 'action_prioritaire', 'tache', 'kpi', 'avancement', 'alerte', 'synthese', 'personnalise']); // Type de rapport
            $table->enum('format', ['pdf', 'excel', 'csv', 'html'])->default('pdf'); // Format de sortie
            $table->enum('periode', ['jour', 'semaine', 'mois', 'trimestre', 'semestre', 'annee', 'personnalise'])->default('mois'); // Période couverte
            $table->date('date_debut')->nullable(); // Date de début (si personnalisé)
            $table->date('date_fin')->nullable(); // Date de fin (si personnalisé)
            $table->json('filtres')->nullable(); // Filtres appliqués (JSON)
            $table->json('parametres')->nullable(); // Paramètres spécifiques (JSON)
            $table->enum('statut', ['brouillon', 'planifie', 'genere', 'envoye', 'archive'])->default('brouillon'); // Statut du rapport
            $table->string('fichier_genere')->nullable(); // Chemin du fichier généré
            $table->integer('taille_fichier')->nullable(); // Taille du fichier en octets
            $table->timestamp('date_generation')->nullable(); // Date de génération
            $table->timestamp('date_envoi')->nullable(); // Date d'envoi
            $table->boolean('est_automatique')->default(false); // Génération automatique (cron)
            $table->string('frequence_cron')->nullable(); // Fréquence si automatique (daily, weekly, monthly)
            $table->text('destinataires')->nullable(); // Liste des destinataires (emails)
            $table->text('notes')->nullable(); // Notes internes
            $table->foreignId('cree_par_id')->nullable()->constrained('users')->nullOnDelete(); // Créateur
            $table->foreignId('papa_id')->nullable()->constrained('papas')->nullOnDelete(); // PAPA associé (si applicable)
            $table->foreignId('objectif_id')->nullable()->constrained('objectifs')->nullOnDelete(); // Objectif associé (si applicable)
            $table->integer('nombre_vues')->default(0); // Nombre de fois consulté
            $table->integer('nombre_telechargements')->default(0); // Nombre de téléchargements
            $table->timestamps();
            $table->softDeletes();

            // Index pour performance
            $table->index(['type', 'statut', 'date_generation']);
            $table->index(['cree_par_id', 'date_generation']);
            $table->index('est_automatique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
