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
        // 1. Books
        Schema::create('hadith_books', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('writer')->nullable();
            $table->unsignedSmallInteger('writer_death_year')->nullable();
            $table->unsignedSmallInteger('chapter_count')->nullable();
            $table->unsignedSmallInteger('hadith_count')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        Schema::create('hadith_book_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_book_id')->constrained('hadith_books')->onDelete('CASCADE');

            $table->string('lang');
            $table->string('name');
            $table->string('writer')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('RESTRICT');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hadith_book_id', 'lang']);
            $table->index('is_active');
            $table->index('created_by');
        });

        // 2. Chapters
        Schema::create('hadith_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_book_id')->constrained('hadith_books')->onDelete('CASCADE');

            $table->unsignedSmallInteger('chapter_number');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hadith_book_id', 'chapter_number']);
            $table->index('is_active');
        });

        Schema::create('hadith_chapter_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_chapter_id')->constrained('hadith_chapters')->onDelete('CASCADE');

            $table->string('lang');
            $table->string('name');
            $table->foreignId('created_by')->constrained('users')->onDelete('RESTRICT');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hadith_chapter_id', 'lang']);
            $table->index('is_active');
            $table->index('created_by');
        });

        // 3. Hadiths
        Schema::create('hadith_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_book_id')->constrained('hadith_books')->onDelete('CASCADE');
            $table->foreignId('hadith_chapter_id')->constrained('hadith_chapters')->onDelete('CASCADE');

            $table->unsignedSmallInteger('chapter_number')->nullable();
            $table->unsignedInteger('hadith_number')->nullable();
            $table->text('heading')->nullable();
            $table->longText('text')->nullable();
            $table->unsignedSmallInteger('volume')->nullable();
            $table->string('status')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hadith_book_id', 'hadith_chapter_id', 'hadith_number'], 'unique_book_chapter_hadith');
            $table->index('is_active');
        });

        Schema::create('hadith_verse_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_verse_id')->constrained('hadith_verses')->onDelete('CASCADE');

            $table->string('lang');
            $table->text('heading')->nullable();
            $table->text('text')->nullable();
            $table->foreignId('created_by')->constrained('users')->default(1);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hadith_verse_id', 'lang']);
            $table->index('is_active');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hadith_verse_translations');
        Schema::dropIfExists('hadith_verses');
        Schema::dropIfExists('hadith_chapter_translations');
        Schema::dropIfExists('hadith_chapters');
        Schema::dropIfExists('hadith_book_translations');
        Schema::dropIfExists('hadith_books');
    }
};
