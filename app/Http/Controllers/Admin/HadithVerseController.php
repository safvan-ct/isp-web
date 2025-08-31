<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HadithChapter;
use App\Models\HadithVerse;
use App\Repository\Hadith\HadithBookInterface;
use App\Repository\Hadith\HadithVerseInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithVerseController extends Controller implements HasMiddleware
{
    public function __construct(
        protected HadithVerseInterface $HadithVerseRepository,
        protected HadithBookInterface $HadithBookRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-verses'), only: ['index', 'dataTable', 'chapter']),
            new Middleware(PermissionMiddleware::using('update hadith-verse'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-verse'), only: ['status']),
        ];
    }

    public function index()
    {
        $books = $this->HadithBookRepository->getAll();
        return view('admin.hadith.verse', compact('books'));
    }

    public function update(Request $request, HadithVerse $hadithVerse)
    {
        $request->validate(['heading' => 'nullable|string', 'text' => 'required|string']);

        try {
            $this->HadithVerseRepository->update($request->only(['heading', 'text']), $hadithVerse);
            return response()->json(['message' => 'Hadith updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->HadithVerseRepository->dataTable($request->book_id, $request->chapter_id);
        return DataTables::of($results)->make(true);
    }

    public function chapter($bookId)
    {
        $results = HadithChapter::select('id', 'hadith_book_id', 'name')->where('hadith_book_id', $bookId)->get();
        return response()->json($results);
    }

    public function status(string $id)
    {
        try {
            $this->HadithVerseRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
