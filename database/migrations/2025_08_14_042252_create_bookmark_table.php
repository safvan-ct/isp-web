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
        Schema::create('bookmark_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
        });

        Schema::create('bookmark_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bookmark_collection_id')->constrained('bookmark_collections')->onDelete('cascade')->nulllable();
            $table->morphs('bookmarkable'); // type + id for polymorphic relation
            $table->timestamps();

            $table->unique(['user_id', 'bookmark_collection_id', 'bookmarkable_id', 'bookmarkable_type'], 'bookmark_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmark_items');
        Schema::dropIfExists('bookmark_collections');
    }
};
