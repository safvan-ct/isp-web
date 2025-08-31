<?php

use App\Http\Controllers\FetchTopicController;
use App\Http\Controllers\HadithFetchController;
use App\Http\Controllers\QuranFetchController;
use App\Http\Controllers\Web\BookmarkCollectionController;
use App\Http\Controllers\Web\BookmarkController;
use App\Http\Controllers\Web\HadithController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LikeController;
use App\Http\Controllers\Web\QuranController;
use App\Http\Controllers\Web\TopicController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';

// ------------------------------
// General Pages
// ------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('calendar', [HomeController::class, 'calendar'])->name('calendar');
Route::get('likes', [HomeController::class, 'likes'])->name('likes');
Route::get('change-language/{lang}', [HomeController::class, 'changeLanguage'])->name('change.language');

// ------------------------------
// Fetch (AJAX / API-like Endpoints)
// ------------------------------
Route::prefix('fetch')->name('fetch.')->group(function () {
    // Quran
    Route::controller(QuranFetchController::class)->group(function () {
        Route::get('quran-chapters', 'fetchChapters')->name('quran.chapters');
        Route::get('quran-ayahs', 'fetchVerses')->name('quran.ayahs');
        Route::get('quran-verse/{id}', 'fetchVerseById')->name('quran.verse');
        Route::post('quran-like', 'fetchLikedVerses')->name('quran.like');
        Route::post('quran-bookmark', 'fetchBookmarkedVerses')->name('quran.bookmark');
    });

    // Hadith
    Route::controller(HadithFetchController::class)->group(function () {
        Route::get('hadith-books', 'fetchBooks')->name('hadith.books');
        Route::get('hadith-chapters', 'fetchChapters')->name('hadith.chapters');
        Route::get('hadith-verses', 'fetchVerses')->name('hadith.verses');
        Route::get('hadith-verse/{id}', 'fetchVerse')->name('hadith.verse');
        Route::post('hadith-like', 'fetchLikedVerses')->name('hadith.like');
        Route::post('hadith-bookmark', 'fetchBookmarkedVerses')->name('hadith.bookmark');
    });

    // Topics
    Route::controller(FetchTopicController::class)->group(function () {
        Route::post('topic-like', 'fetchLikedTopics')->name('topic.like');
        Route::post('topic-bookmark', 'fetchBookmarkedTopics')->name('topic.bookmark');
    });
});

// ------------------------------
// Authenticated (Customer) Routes
// ------------------------------
Route::middleware(['auth', 'customer'])->group(function () {
    // Likes
    Route::controller(LikeController::class)->group(function () {
        Route::post('like-item', 'store')->name('like.toggle');
        Route::post('sync-likes', 'sync')->name('likes.sync');
    });

    // Bookmarks
    Route::controller(BookmarkController::class)->group(function () {
        Route::post('bookmark-item', 'store')->name('bookmark.toggle');
    });

    // Collections
    Route::get('fetch/collections', [BookmarkCollectionController::class, 'fetchCollections'])->name('fetch.collections');
    Route::resource('collections', BookmarkCollectionController::class)->except(['create', 'edit']);
});

// ------------------------------
// Quran Routes
// ------------------------------
Route::controller(QuranController::class)->prefix('quran')->name('quran.')->group(function () {
    Route::get('/', 'quran')->name('index');
    Route::get('{id}', 'quranChapter')->name('chapter');
});

// ------------------------------
// Hadith Routes
// ------------------------------
Route::controller(HadithController::class)->prefix('hadith')->name('hadith.')->group(function () {
    Route::get('/', 'hadith')->name('index');
    Route::get('{book}/chapters/{chapter?}', 'hadithChapters')->name('chapters');
    Route::get('{book}/chapter/{chapter}', 'hadithChapterVerses')->name('chapter.verses');
    Route::get('{book}/verse/{verse}', 'hadithVerseByNumber')->name('book.verse');
});

// ------------------------------
// Topic Routes (Catch-All)
// ------------------------------
Route::get('{slug}', [TopicController::class, 'modules'])->name('modules.show');
Route::get('{menu_slug}/{module_slug}', [TopicController::class, 'questions'])->name('questions.show');
Route::get('{menu_slug}/{module_slug}/{question_slug}', [TopicController::class, 'answers'])->name('answers.show');
