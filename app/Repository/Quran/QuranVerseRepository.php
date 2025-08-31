<?php
namespace App\Repository\Quran;

use App\Models\BookmarkItem;
use App\Models\Like;
use App\Models\QuranVerse;
use App\Services\ApiService;

class QuranVerseRepository implements QuranVerseInterface
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getById($id)
    {
        return QuranVerse::find($id);
    }

    public function dataTable($chapterId)
    {
        return QuranVerse::where('quran_chapter_id', $chapterId);
    }

    public function status($id)
    {
        $query = $this->getById($id);
        if (! $query) {
            throw new \Exception('Item not found');
        }

        $query->update(['is_active' => ! $query->is_active]);
        return $query;
    }

    public function update(array $data, QuranVerse $quranVerse)
    {
        $quranVerse->update($data);
        return $quranVerse;
    }

    public function getVerseById(array $id, $paginate = false)
    {
        $query = QuranVerse::select('id', 'quran_chapter_id', 'number_in_chapter', 'text')
            ->with([
                'translations',
                'chapter' => fn($q) => $q->select('id', 'name')->with('translations'),
            ])
            ->whereIn('id', $id)
            ->active();

        return $paginate ? $query->paginate(5) : $query->get();
    }

    public function getVerses(int $chapterId, ?int $ayahNumber = null)
    {
        return QuranVerse::select(['id', 'number_in_chapter', 'text'])
            ->where('quran_chapter_id', $chapterId)
            ->when($ayahNumber, fn($q) => $q->where('number_in_chapter', $ayahNumber))
            ->active()
            ->get();
    }

    public function getLikedVerses($userId, $paginate = true)
    {
        $ids = Like::where('likeable_type', 'App\Models\QuranVerse')
            ->where('user_id', $userId)
            ->pluck('likeable_id')
            ->toArray();

        return $this->getVerseById($ids, $paginate);
    }

    public function getBookmarkedVerses($userId, $collectionId, $paginate = true)
    {
        $ids = BookmarkItem::where('bookmarkable_type', 'App\Models\QuranVerse')
            ->where('bookmark_collection_id', $collectionId)
            ->where('user_id', $userId)
            ->pluck('bookmarkable_id')
            ->toArray();

        return $this->getVerseById($ids, $paginate);
    }
}
