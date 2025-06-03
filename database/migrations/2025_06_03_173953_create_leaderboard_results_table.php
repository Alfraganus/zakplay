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
        Schema::create('leaderboard_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leaderboard_id');
            $table->unsignedBigInteger('test_id')->nullable();
            $table->unsignedBigInteger('test_result_id');
            $table->boolean('is_special_leaderboard')->default(false);

            $table->foreign('leaderboard_id')->references('id')->on('leaderboard')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('roadmap_test')->onDelete('set null');
            $table->foreign('test_result_id')->references('id')->on('user_test_results')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_results');
    }
};
