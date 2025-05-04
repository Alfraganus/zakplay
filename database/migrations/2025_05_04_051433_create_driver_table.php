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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->date('date_of_brith');
            $table->string('login')->nullable();
            $table->string('pincode')->unique()->nullable();
            $table->integer('car_model');
            $table->integer('car_color');
            $table->string('car_plate_number');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tablets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_active')->default(1);
            $table->string('current_address');
            $table->unsignedBigInteger('driver_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('tablets');
    }
};
