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
        Schema::create('measurement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->tinyInteger('status')->default(0);
            $table->string('ip', 16);
            $table->timestamps();
            $table->index(['ip', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_requests');
    }
};
