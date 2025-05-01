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
        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('department_name_'); // Add this line
            $table->string('language'); // Add this line
            $table->timestamps();
        });

        Schema::table('roadmap_test', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('id');
            $table->foreign('department_id')->references('id')->on('department')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');

        Schema::table('roadmap_test', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
