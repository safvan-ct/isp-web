<?php
namespace App\Repository\Hadith;

use App\Models\HadithChapterTranslation;

class HadithChapterTranslationRepository implements HadithChapterTranslationInterface
{
    public function getById($id)
    {
        return HadithChapterTranslation::find($id);
    }

    public function dataTable($chapterId)
    {
        return HadithChapterTranslation::where('hadith_chapter_id', $chapterId);
    }

    public function updateOrCreate(array $data, ?HadithChapterTranslation $hadithChapterTranslation = null): HadithChapterTranslation
    {
        return HadithChapterTranslation::updateOrCreate(['id' => $hadithChapterTranslation?->id], $data);
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
}
