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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('title');
            $table->boolean('is_active')->default(true);

            $table->foreign('department_id')
                ->references('id')
                ->on('department')
                ->onDelete('cascade');
        });

        Schema::table('roadmap_test', function (Blueprint $table) {
            $table->integer('ad_place')->nullable();
            $table->integer('ad_after_question')->nullable();
            $table->unsignedBigInteger('ad_id')->nullable();
            $table->integer('views_limit')->nullable();
            $table->integer('time_for_question')->nullable();

            $table->foreign('ad_id')
                ->references('id')
                ->on('ads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roadmap_test', function (Blueprint $table) {
            //
        });
    }
};
