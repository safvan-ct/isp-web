<?php
namespace App\Repository\Hadith;

use App\Models\HadithVerseTranslation;

class HadithVerseTranslationRepository implements HadithVerseTranslationInterface
{
    public function getById($id)
    {
        return HadithVerseTranslation::find($id);
    }

    public function dataTable($verseId)
    {
        return HadithVerseTranslation::where('hadith_verse_id', $verseId);
    }

    public function updateOrCreate(array $data, ?HadithVerseTranslation $hadithVerseTranslation = null): HadithVerseTranslation
    {
        return HadithVerseTranslation::updateOrCreate(['id' => $hadithVerseTranslation?->id], $data);
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
