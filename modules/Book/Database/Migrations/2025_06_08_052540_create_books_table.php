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
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // BookID
            $table->string('title');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->string('edition')->nullable();
            $table->integer('volumes')->default(1);
            $table->integer('pages')->nullable();
            $table->string('source_of_fund')->nullable();
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('category')->nullable();
            $table->string('genre')->nullable();
            $table->string('access_book_number')->unique();
            $table->string('cover_image')->nullable(); // <-- ADD THIS LINE
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
