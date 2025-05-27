<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade')->comment('The organization ID');
            $table->string('title', 200)->comment('The title of the event');
            $table->text('description')->nullable()->comment('The description of the event');
            $table->string('venue', 200)->comment('The venue of the event');
            $table->dateTime('event_date')->comment('The date and time of the event');
            $table->decimal('price', 10, 2)->default(0)->comment('The price of the event');
            $table->integer('max_attendees')->nullable()->comment('The maximum number of attendees for the event');
            $table->boolean('is_active')->default(true)->comment('Whether the event is active');
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index('organization_id');
            $table->index('event_date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
