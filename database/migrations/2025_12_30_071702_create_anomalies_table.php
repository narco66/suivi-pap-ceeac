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
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('titre');
            $table->text('description');
            $table->string('severite', 20)->default('moyenne');
            $table->string('statut', 20)->default('detectee');
            $table->foreignId('tache_id')->nullable()->constrained('taches')->onDelete('cascade');
            $table->foreignId('action_prioritaire_id')->nullable()->constrained('actions_prioritaires')->onDelete('cascade');
            $table->dateTime('date_detection')->nullable();
            $table->dateTime('date_correction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anomalies');
    }
};
