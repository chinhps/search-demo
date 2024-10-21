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
        Schema::create('measurement_keywords', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('measurement_request_id');
            $table->foreign('measurement_request_id')->references('id')->on('measurement_requests');
            $table->string('keyword', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_keywords');
    }
};
