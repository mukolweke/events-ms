<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 200)->unique()->comment('The slug of the organization');
            $table->string('domain', 200)->unique()->comment('The domain of the organization');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('domain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
