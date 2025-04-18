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
        Schema::create('roadmap_test_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->string('device_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id');
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('roadmap_test')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('roadmap_test_questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('roadmap_test_question_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_test_answers');
    }
};
