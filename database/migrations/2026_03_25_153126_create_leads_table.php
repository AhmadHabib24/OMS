<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();

            $table->enum('status', [
                'new',
                'contacted',
                'qualified',
                'proposal_sent',
                'won',
                'lost',
            ])->default('new');

            $table->enum('priority', [
                'low',
                'medium',
                'high',
            ])->default('medium');

            $table->foreignId('assigned_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('next_follow_up_date')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};