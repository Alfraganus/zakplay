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
        Schema::create('user_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained('roadmap_test')->onDelete('cascade');
            $table->integer('test_result');
            $table->float('percentage', 5, 2);
            $table->integer('max_score');
            $table->string('device_id')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->integer('average_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_test_results');
    }
};
