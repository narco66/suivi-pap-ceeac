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
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('users', 'phone')) {
                // Vérifier si telephone existe, sinon ajouter après email
                if (Schema::hasColumn('users', 'telephone')) {
                    $table->string('phone')->nullable()->after('telephone');
                } else {
                    $table->string('phone')->nullable()->after('email');
                }
            }
            if (!Schema::hasColumn('users', 'title')) {
                // Vérifier si fonction existe, sinon ajouter après email
                if (Schema::hasColumn('users', 'fonction')) {
                    $table->string('title')->nullable()->after('fonction'); // Poste/fonction (alias de fonction)
                } else {
                    $table->string('title')->nullable()->after('email');
                }
            }
            if (!Schema::hasColumn('users', 'status')) {
                // Vérifier si actif existe, sinon ajouter après email
                if (Schema::hasColumn('users', 'actif')) {
                    $table->enum('status', ['actif', 'suspendu', 'inactif'])->default('actif')->after('actif');
                } else {
                    $table->enum('status', ['actif', 'suspendu', 'inactif'])->default('actif')->after('email');
                }
            }
            if (!Schema::hasColumn('users', 'structure_id')) {
                // Vérifier si status existe, sinon ajouter après email
                if (Schema::hasColumn('users', 'status')) {
                    $table->unsignedBigInteger('structure_id')->nullable()->after('status');
                } else {
                    $table->unsignedBigInteger('structure_id')->nullable()->after('email');
                }
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('users', 'metadata')) {
                $table->json('metadata')->nullable()->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['structure_id']);
            $table->dropColumn([
                'phone',
                'title',
                'status',
                'structure_id',
                'last_login_at',
                'avatar',
                'metadata',
            ]);
        });
    }
};

