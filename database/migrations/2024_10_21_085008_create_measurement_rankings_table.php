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
        Schema::create('measurement_rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('measurement_keyword_id');
            $table->foreign('measurement_keyword_id')->references('id')->on('measurement_keywords');
            $table->unsignedBigInteger('ranking_source_id');
            $table->foreign('ranking_source_id')->references('id')->on('ranking_sources');
            $table->tinyInteger('rank')->nullable();
            $table->integer('results_counter');
            $table->timestamp('retrieved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_rankings');
    }
};
