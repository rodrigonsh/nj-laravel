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
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('helper_1')->nullable()->constrained('users');
            $table->foreignId('helper_2')->nullable()->constrained('users');
            $table->string('need');
            $table->text('confession');
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->timestamp('resolved_at')->nullable();
            $table->string('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_request');
    }
};
