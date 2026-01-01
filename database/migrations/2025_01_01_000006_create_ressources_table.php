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
        Schema::create('ressources', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('type'); // excel, pdf, zip, doc, autre
            $table->string('categorie')->default('general'); // general, import, export, documentation, template, autre
            $table->string('version')->default('1.0');
            $table->string('fichier')->nullable(); // Chemin du fichier dans storage
            $table->string('nom_fichier_original')->nullable(); // Nom original du fichier
            $table->integer('taille_fichier')->nullable(); // Taille en octets
            $table->string('mime_type')->nullable(); // Type MIME du fichier
            $table->boolean('est_public')->default(true); // Accessible sans authentification
            $table->boolean('est_actif')->default(true);
            $table->integer('nombre_telechargements')->default(0);
            $table->foreignId('cree_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_publication')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'categorie', 'est_actif']);
            $table->index('est_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};



