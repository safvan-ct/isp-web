<?php
namespace App\Repository\Quran;

use App\Models\QuranVerseTranslation;

class QuranVerseTranslationRepository implements QuranVerseTranslationInterface
{
    public function getById($id)
    {
        return QuranVerseTranslation::find($id);
    }

    public function dataTable($chapterId, $lang)
    {
        return QuranVerseTranslation::where('quran_chapter_id', $chapterId)->where('lang', $lang);
    }

    public function update(array $data, QuranVerseTranslation $quranVerseTranslation): QuranVerseTranslation
    {
        $quranVerseTranslation->update($data);
        return $quranVerseTranslation;
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
