<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HadithVerse;
use App\Repository\Hadith\HadithBookInterface;
use App\Repository\Hadith\HadithChapterInterface;
use App\Repository\Hadith\HadithVerseInterface;

class HadithController extends Controller
{
    protected $webVersion;

    public function __construct(
        protected HadithBookInterface $hadithBookRepository,
        protected HadithChapterInterface $hadithChapterRepository,
        protected HadithVerseInterface $hadithVerseRepository
    ) {
        $this->webVersion = config("constants.web_version");
    }

    public function hadith()
    {
        $books = $this->hadithBookRepository->getWithTranslations();
        return view("web.hadith-books", compact("books"));
    }

    public function hadithChapters($bookId, $chapterId = null)
    {
        $book      = $this->hadithBookRepository->getWithChapters($bookId);
        $chapterId = $chapterId ?? $book->chapters->first()->id;
        $verses    = HadithVerse::select('id', 'hadith_book_id', 'hadith_chapter_id', 'chapter_number', 'hadith_number', 'heading', 'text', 'volume', 'status')
            ->with(['translations'])
            ->where('hadith_chapter_id', $chapterId)
            ->active()
            ->paginate(3);
        return view("web.hadith-chapters", compact("book", "verses"));
    }

    public function hadithChapterVerses($bookSlug, $chapterId)
    {
        $chapter = $this->hadithChapterRepository->getWithAll($chapterId);
        if (! $chapter) {
            abort(404);
        }

        return view("web.hadith-verses", compact("chapter"));
    }

    public function hadithVerseByNumber($bookId, $verseNumber)
    {
        $hadithVerse = $this->hadithVerseRepository->getByWhere([
            "hadith_number"  => $verseNumber,
            "hadith_book_id" => $bookId,
        ]);
        if (! $hadithVerse) {
            abort(404);
        }

        $chapter = $this->hadithChapterRepository->getWithAll($hadithVerse->hadith_chapter_id, $verseNumber);
        if (! $chapter) {
            abort(404);
        }

        return view("web.hadith-verses", compact("chapter", "verseNumber"));
    }
}
