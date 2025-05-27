<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade')->comment('The event ID');
            $table->string('name', 200)->comment('The name of the attendee');
            $table->string('email', 200)->comment('The email of the attendee');
            $table->string('phone', 200)->nullable()->comment('The phone number of the attendee');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            // Add indexes
            $table->index('event_id');
            $table->index('email');
            $table->index('registered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
