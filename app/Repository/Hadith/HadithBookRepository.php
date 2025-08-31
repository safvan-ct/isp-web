<?php
namespace App\Repository\Hadith;

use App\Models\HadithBook;

class HadithBookRepository implements HadithBookInterface
{
    public function getById($id)
    {
        return HadithBook::find($id);
    }

    public function dataTable()
    {
        return HadithBook::select('id', 'name', 'slug', 'writer', 'writer_death_year', 'chapter_count', 'hadith_count', 'is_active');
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

    public function update(array $data, HadithBook $hadithBook)
    {
        return HadithBook::updateOrCreate(['id' => $hadithBook->id], $data);
    }

    public function getAll()
    {
        return HadithBook::select('id', 'name', 'writer', 'writer_death_year', 'hadith_count', 'chapter_count')
            ->active()
            ->get();
    }

    public function getWithTranslations()
    {
        return HadithBook::select('id', 'name', 'slug', 'writer', 'writer_death_year', 'chapter_count', 'hadith_count')
            ->with('translations')
            ->active()
            ->get();
    }

    public function getWithChapters($id = null)
    {
        $query = HadithBook::select('id', 'name', 'slug', 'writer', 'writer_death_year', 'chapter_count', 'hadith_count')
            ->with([
                'translations',
                'chapters' => fn($q) => $q
                    ->select('id', 'hadith_book_id', 'chapter_number', 'name')
                    ->with('translations')
                    ->active(),
            ])
            ->active();

        return $id ? $query->find($id) : $query->get();
    }
}
