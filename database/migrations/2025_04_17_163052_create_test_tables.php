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
        Schema::create('roadmap_test', function (Blueprint $table) {
            $table->id();
            $table->text('title_')->nullable();
            $table->text('description_')->nullable();
            $table->timestamps();
        });

        Schema::create('roadmap_test_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->text('question_text_')->nullable();
            $table->string('question_option_type');
            $table->integer('points')->nullable();
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('roadmap_test')->onDelete('cascade');
        });

        Schema::create('roadmap_test_question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->text('option_text_')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('roadmap_test_questions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_test_question_options');
        Schema::dropIfExists('roadmap_test_questions');
        Schema::dropIfExists('roadmap_test');
    }
};
