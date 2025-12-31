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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // created, updated, deleted, viewed, exported, etc.
            $table->string('object_type')->nullable(); // App\Models\User, App\Models\Papa, etc.
            $table->unsignedBigInteger('object_id')->nullable();
            $table->json('metadata')->nullable(); // Diff, old values, new values, etc.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('module')->nullable(); // admin, papa, objectif, etc.
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['actor_id', 'created_at']);
            $table->index(['object_type', 'object_id']);
            $table->index(['action', 'created_at']);
            $table->index('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};


