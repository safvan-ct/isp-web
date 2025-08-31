<?php
namespace App\Http\Controllers;

use App\Repository\Hadith\HadithBookTranslationInterface;
use App\Repository\Hadith\HadithChapterInterface;
use App\Repository\Hadith\HadithVerseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HadithFetchController extends Controller
{
    public function __construct(
        protected HadithBookTranslationInterface $hadithBookTranslationRepository,
        protected HadithChapterInterface $hadithChapterRepository,
        protected HadithVerseInterface $hadithVerseRepository
    ) {}

    public function fetchBooks(Request $request)
    {
        $books = $this->hadithBookTranslationRepository->getBooks($request->get('name'));
        return response()->json($books);
    }

    public function fetchChapters(Request $request)
    {
        $chapters = $this->hadithChapterRepository->getChpaters($request->get('hadith_book_id'), $request->get('name'));
        return response()->json($chapters);
    }

    public function fetchVerses(Request $request)
    {
        $verses = $this->hadithVerseRepository->getVersesByChapter($request->get('hadith_chapter_id'), $request->get('search'));
        return response()->json($verses);
    }

    public function fetchVerse($id)
    {
        $result = $this->hadithVerseRepository->getVerseById([$id]);
        return response()->json(['html' => view('web.partials.hadith-list', ['result' => $result, 'action' => false])->render()]);
    }

    public function fetchLikedVerses(Request $request)
    {
        if (Auth::check() && Auth::user()->role == 'Customer') {
            $result = $this->hadithVerseRepository->getLikedVerses(Auth::id());
        } else {
            $ids    = array_values(array_filter($request->ids));
            $result = $this->hadithVerseRepository->getVerseById($ids, true);
        }

        return response()->json([
            'html'       => view('web.partials.hadith-list', ['result' => $result, 'liked' => true])->render(),
            'pagination' => view('components.web.pagination', ['paginator' => $result])->render(),
        ]);
    }

    public function fetchBookmarkedVerses(Request $request)
    {
        $result = $this->hadithVerseRepository->getBookmarkedVerses(Auth::id(), $request->get('collection_id'));

        return response()->json([
            'html'       => view('web.partials.hadith-list', ['result' => $result, 'bookmarked' => true])->render(),
            'pagination' => view('components.web.pagination', ['paginator' => $result])->render(),
        ]);
    }
}
