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
        Schema::table('taches', function (Blueprint $table) {
            // Baseline dates (pour comparaison prévu vs réel)
            $table->dateTime('baseline_start')->nullable()->after('date_debut_prevue');
            $table->dateTime('baseline_end')->nullable()->after('date_fin_prevue');
            
            // Métadonnées Gantt
            $table->string('gantt_color', 7)->nullable()->after('est_jalon'); // Hex color code
            $table->integer('gantt_sort_order')->default(0)->after('gantt_color');
            $table->boolean('is_critical')->default(false)->after('gantt_sort_order');
            $table->text('gantt_notes')->nullable()->after('is_critical');
            
            // Index pour performance
            $table->index('gantt_sort_order');
            $table->index('is_critical');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropColumn([
                'baseline_start',
                'baseline_end',
                'gantt_color',
                'gantt_sort_order',
                'is_critical',
                'gantt_notes',
            ]);
        });
    }
};
