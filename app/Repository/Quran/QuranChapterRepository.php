<?php
namespace App\Repository\Quran;

use App\Models\QuranChapter;

class QuranChapterRepository implements QuranChapterInterface
{
    public function getById($id)
    {
        return QuranChapter::find($id);
    }

    public function dataTable()
    {
        return QuranChapter::select('id', 'name', 'revelation_place', 'no_of_verses', 'is_active');
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

    public function update(array $data, QuranChapter $quranChapter): QuranChapter
    {
        return QuranChapter::updateOrCreate(['id' => $quranChapter->id], $data);
    }

    public function getAll()
    {
        return QuranChapter::select('id', 'name')
            ->active()
            ->get();
    }

    public function getWithTranslations()
    {
        return QuranChapter::select('id', 'name', 'no_of_verses', 'revelation_place')
            ->with('translations')
            ->active()
            ->get();
    }

    public function getWithVerses($id = null)
    {
        $query = QuranChapter::select('id', 'name', 'no_of_verses', 'revelation_place')
            ->with([
                'translations',
                'verses' => fn($q) => $q
                    ->select('id', 'quran_chapter_id', 'number_in_chapter', 'text')
                    ->with('translations')
                    ->active(),
            ])
            ->whereHas('verses', fn($q) => $q->active())
            ->active();

        return $id ? $query->find($id) : $query->get();
    }
}
