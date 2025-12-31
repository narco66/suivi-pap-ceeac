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
        Schema::create('avancements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade');
            $table->dateTime('date_avancement');
            $table->integer('pourcentage_avancement')->default(0);
            $table->text('commentaire')->nullable();
            $table->string('fichier_joint')->nullable();
            $table->foreignId('soumis_par_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('valide_par_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_validation')->nullable();
            $table->string('statut', 20)->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avancements');
    }
};
