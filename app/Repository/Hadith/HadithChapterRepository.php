<?php
namespace App\Repository\Hadith;

use App\Models\HadithChapter;

class HadithChapterRepository implements HadithChapterInterface
{
    public function getById($id)
    {
        return HadithChapter::find($id);
    }

    public function dataTable($bookId)
    {
        return HadithChapter::where('hadith_book_id', $bookId);
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

    public function update(array $data, HadithChapter $hadithChapter)
    {
        $hadithChapter->update($data);
        return $hadithChapter;
    }

    public function getWithAll($id = null, $hadithNumber = null)
    {
        $query = HadithChapter::select('id', 'hadith_book_id', 'name', 'chapter_number')
            ->with([
                'translations',
                'verses' => fn($q) => $q
                    ->select('id', 'hadith_chapter_id', 'heading', 'text', 'chapter_number', 'hadith_number', 'heading', 'text', 'volume', 'status')
                    ->with('translations')
                    ->when($hadithNumber, fn($q) => $q->where('hadith_number', $hadithNumber))
                    ->active(),

                'book'   => fn($q)   => $q
                    ->select('id', 'name', 'slug', 'writer', 'writer_death_year', 'hadith_count', 'chapter_count')
                    ->with('translations')
                    ->active(),
            ])
            ->whereHas('verses', fn($q) => $q->active()->when($hadithNumber, fn($q) => $q->where('hadith_number', $hadithNumber)))
            ->active();

        return $id ? $query->find($id) : $query->get();
    }

    public function getChpaters($bookId, $name = null)
    {
        return HadithChapter::select(['id', 'chapter_number', 'name'])
            ->with([
                'translations' => fn($q) => $q
                    ->select(['id', 'hadith_chapter_id', 'name'])
                    ->when(! empty($name) && ! is_numeric($name), fn($q) => $q->where('name', 'like', '%' . $name . '%'))
                    ->active()
                    ->lang('en'),
            ])
            ->when(! empty($name), function ($q) use ($name) {
                $q->where(function ($query) use ($name) {
                    $query->whereHas('translations', fn($q) => $q->where('name', 'like', '%' . $name . '%'));

                    if (is_numeric($name)) {
                        $query->orWhere('chapter_number', $name);
                    }
                });
            })
            ->where('hadith_book_id', $bookId)
            ->active()
            ->get();
    }
}
