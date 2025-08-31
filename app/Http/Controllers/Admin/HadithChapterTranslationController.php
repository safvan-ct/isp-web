<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hadith\HadithChapterTranslationStoreRequest;
use App\Models\HadithChapterTranslation;
use App\Repository\Hadith\HadithChapterInterface;
use App\Repository\Hadith\HadithChapterTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithChapterTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected HadithChapterInterface $HadithChapterRepository,
        protected HadithChapterTranslationInterface $HadithChapterTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-chapter-translations'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store hadith-chapter-translation'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update hadith-chapter-translation'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-chapter-translation'), only: ['status']),
        ];
    }

    public function index($chapterId, $translationId = null)
    {
        $chapter     = $this->HadithChapterRepository->getById($chapterId);
        $translation = $this->HadithChapterTranslationRepository->getById($translationId);

        return view('admin.hadith.chapter-translation', compact('chapter', 'translation'));
    }

    public function store(HadithChapterTranslationStoreRequest $request)
    {
        try {
            $this->persist($request);
            return redirect()->route('admin.hadith-chapter-translations.index', [$request->hadith_chapter_id])
                ->with('success', 'Chapter translation created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-chapter-translations.index', [$request->hadith_chapter_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(HadithChapterTranslationStoreRequest $request, HadithChapterTranslation $hadithChapterTranslation)
    {
        try {
            $this->persist($request, $hadithChapterTranslation);
            return to_route('admin.hadith-chapter-translations.index', [$request->hadith_chapter_id])
                ->with('success', 'Chapter translation updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-chapter-translations.index', [$request->hadith_chapter_id, $hadithChapterTranslation->id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->HadithChapterTranslationRepository->dataTable($request->chapter_id);
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->HadithChapterTranslationRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(HadithChapterTranslationStoreRequest $request, ?HadithChapterTranslation $hadithChapterTranslation = null): HadithChapterTranslation
    {
        $data               = $request->validated();
        $data['created_by'] = Auth::user()->id;

        return $this->HadithChapterTranslationRepository->updateOrCreate($data, $hadithChapterTranslation);
    }
}
