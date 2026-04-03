<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // campaign_content, lead_insight
            $table->string('model')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('email_campaign_id')->nullable()->constrained()->nullOnDelete();

            $table->json('input_payload')->nullable();
            $table->json('output_payload')->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('response_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();
            $table->string('status')->default('success'); // success / failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};