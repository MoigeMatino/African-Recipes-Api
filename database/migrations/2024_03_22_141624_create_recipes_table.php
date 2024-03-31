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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('instructions');
            $table->string('prep_time');
            $table->string('cook_time');
            $table->string('total_time');
            $table->integer('servings');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->json('ingredients');
            $table->boolean('premium')->default(false);
            $table->json('nutritional_info');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
