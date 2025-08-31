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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('topics')->onDelete('cascade');
            $table->string('slug');
            $table->string('type')->default('topic'); // menu, module, question, answer
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['parent_id', 'slug']);
        });

        Schema::create('topic_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->string('lang', 5)->index();
            $table->string('title');
            $table->text('sub_title')->nullable()->fulltext();
            $table->text('content')->nullable()->fulltext(); // used only for topic/point
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['topic_id', 'lang']);
        });

        Schema::create('topic_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->string('video_id')->index();
            $table->json('title')->nullable(); // optional title
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['topic_id', 'video_id']);
        });

        Schema::create('topic_hadith', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('hadith_verse_id')->constrained('hadith_verses')->onDelete('cascade');
            $table->text('simplified')->nullable();
            $table->json('translation_json')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['topic_id', 'hadith_verse_id']);
        });

        Schema::create('topic_quran_verse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('quran_verse_id')->constrained('quran_verses')->onDelete('cascade');
            $table->text('simplified')->nullable();
            $table->json('translation_json')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['topic_id', 'quran_verse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_quran_verse');
        Schema::dropIfExists('topic_hadith');
        Schema::dropIfExists('topic_videos');
        Schema::dropIfExists('topic_translations');
        Schema::dropIfExists('topics');
    }
};
