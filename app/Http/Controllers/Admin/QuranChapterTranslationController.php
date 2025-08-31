<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quran\ChapterTranslationStoreRequest;
use App\Models\QuranChapterTranslation;
use App\Repository\Quran\QuranChapterInterface;
use App\Repository\Quran\QuranChapterTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class QuranChapterTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected QuranChapterInterface $QuranChapterRepository,
        protected QuranChapterTranslationInterface $QuranChapterTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view quran-chapter-translations'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store quran-chapter-translation'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update quran-chapter-translation'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active quran-chapter-translation'), only: ['status']),
        ];
    }

    public function index($chapterId, $translationId = null)
    {
        $chapter = $this->QuranChapterRepository->getById($chapterId);
        if (! $chapter) {
            abort(404);
        }

        $translation = $this->QuranChapterTranslationRepository->getById($translationId);
        return view('admin.quran.chapter-translation', compact('chapter', 'translation'));
    }

    public function store(ChapterTranslationStoreRequest $request)
    {
        try {
            $this->persist($request);
            return redirect()->route('admin.quran-chapter-translations.index', [$request->quran_chapter_id])
                ->with('success', 'Chapter translation created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.quran-chapter-translations.index', [$request->quran_chapter_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(ChapterTranslationStoreRequest $request, QuranChapterTranslation $quranChapterTranslation)
    {
        try {
            $this->persist($request, $quranChapterTranslation);
            return to_route('admin.quran-chapter-translations.index', [$request->quran_chapter_id])
                ->with('success', 'Chapter translation updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.quran-chapter-translations.index', [$request->quran_chapter_id, $quranChapterTranslation->id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->QuranChapterTranslationRepository->dataTable($request->chapter_id);
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->QuranChapterTranslationRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(ChapterTranslationStoreRequest $request, ?QuranChapterTranslation $quranChapterTranslation = null): QuranChapterTranslation
    {
        $data               = $request->validated();
        $data['created_by'] = Auth::user()->id;

        return $this->QuranChapterTranslationRepository->updateOrCreate($data, $quranChapterTranslation);
    }
}
