<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade')->comment('The organization ID');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action', 250)->comment('The action performed');
            $table->string('model_type', 250)->comment('The type of model');
            $table->unsignedBigInteger('model_id')->comment('The ID of the model');
            $table->json('old_values')->nullable()->comment('The old values of the model');
            $table->json('new_values')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('organization_id');
            $table->index('user_id');
            $table->index(['model_type', 'model_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
