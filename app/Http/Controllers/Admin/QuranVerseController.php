<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranVerse;
use App\Repository\Quran\QuranChapterInterface;
use App\Repository\Quran\QuranVerseInterface;
use App\Repository\Quran\QuranVerseTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class QuranVerseController extends Controller implements HasMiddleware
{
    public function __construct(
        protected QuranVerseInterface $QuranVerseRepository,
        protected QuranChapterInterface $QuranChapterRepository,
        protected QuranVerseTranslationInterface $QuranVerseTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view quran-verses'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('update quran-verse'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active quran-verse'), only: ['status']),
        ];
    }

    public function index()
    {
        $chapters = $this->QuranChapterRepository->getAll();
        return view('admin.quran.chapter-verse', compact('chapters'));
    }

    public function update(Request $request, QuranVerse $quranVerse)
    {
        $request->validate(['text' => 'required']);

        try {
            $this->QuranVerseRepository->update($request->only(['text']), $quranVerse);
            return response()->json(['message' => 'Verse updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dataTable(Request $request)
    {
        if ($request->lang == 'ar') {
            $results = $this->QuranVerseRepository->dataTable($request->chapter_id);
        } else {
            $results = $this->QuranVerseTranslationRepository->dataTable($request->chapter_id, $request->lang);
        }

        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->QuranVerseRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
