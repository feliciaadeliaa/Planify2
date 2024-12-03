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
        Schema::create('column', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->references('id')->on('project')->onDelete('cascade');
            $table->string('column_name');
            $table->integer('status')->default(1);
            $table->bigInteger('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('column');
    }
};
