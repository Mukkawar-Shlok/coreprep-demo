<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->text('question_text');

            // Option fields (up to 5)
            $table->string('option_1')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_4')->nullable();
            $table->string('option_5')->nullable();

            // Correct option number (1-5)
            $table->unsignedTinyInteger('correct_option')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('questions');
    }
};
