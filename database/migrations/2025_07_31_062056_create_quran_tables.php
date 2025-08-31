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
        // 1. Surahs Table
        Schema::create('quran_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('revelation_place');
            $table->unsignedSmallInteger('no_of_verses');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('name');
            $table->index('no_of_verses');
        });

        Schema::create('quran_chapter_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quran_chapter_id')->constrained('quran_chapters')->onDelete('CASCADE');

            $table->string('lang');
            $table->string('name');
            $table->string('translation')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('RESTRICT');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['quran_chapter_id', 'lang']); // Only one translation per chapter per lang

            $table->index('quran_chapter_id');
            $table->index('lang');
            $table->index('is_active');
            $table->index('created_by');
        });

        // 2. Verses Table
        Schema::create('quran_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quran_chapter_id')->constrained('quran_chapters')->onDelete('CASCADE');

            $table->unsignedSmallInteger('number_in_chapter');
            $table->text('text');
            $table->unsignedTinyInteger('juz');
            $table->unsignedTinyInteger('manzil');
            $table->unsignedSmallInteger('ruku')->nullable();
            $table->unsignedSmallInteger('hizb_quarter')->nullable();
            $table->unsignedTinyInteger('sajda')->default(0)->comment('0 = false, 1 = recommended, 2 = obligatory');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['quran_chapter_id', 'number_in_chapter']); // Ensure no duplicate verse numbers

            $table->index('quran_chapter_id');
            $table->index('number_in_chapter');
            $table->index('is_active');
        });

        Schema::create('quran_verse_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quran_chapter_id')->constrained('quran_chapters')->onDelete('CASCADE');
            $table->foreignId('quran_verse_id')->constrained('quran_verses')->onDelete('CASCADE');

            $table->unsignedSmallInteger('number_in_chapter');
            $table->string('lang');
            $table->text('text');
            $table->foreignId('created_by')->constrained('users')->onDelete('RESTRICT');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['quran_verse_id', 'lang']); // Only one translation per verse per lang

            $table->index('quran_verse_id');
            $table->index('lang');
            $table->index('is_active');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_verse_translations');
        Schema::dropIfExists('quran_verses');
        Schema::dropIfExists('quran_chapter_translations');
        Schema::dropIfExists('quran_chapters');
    }
};
