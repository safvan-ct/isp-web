<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hadith\HadithVerseTranslationStoreRequest;
use App\Models\HadithVerseTranslation;
use App\Repository\Hadith\HadithVerseInterface;
use App\Repository\Hadith\HadithVerseTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithVerseTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected HadithVerseInterface $HadithVerseRepository,
        protected HadithVerseTranslationInterface $HadithVerseTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-verse-translations'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store hadith-verse-translation'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update hadith-verse-translation'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-verse-translation'), only: ['status']),
        ];
    }

    public function index($verseId, $translationId = null)
    {
        $verse       = $this->HadithVerseRepository->getById($verseId);
        $translation = $this->HadithVerseTranslationRepository->getById($translationId);

        return view('admin.hadith.verse-translations', compact('verse', 'translation'));
    }

    public function store(HadithVerseTranslationStoreRequest $request)
    {
        try {
            $this->persist($request);
            return redirect()->route('admin.hadith-verse-translations.index', [$request->hadith_verse_id])
                ->with('success', 'Verse translation created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-verse-translations.index', [$request->hadith_verse_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(HadithVerseTranslationStoreRequest $request, HadithVerseTranslation $hadithVerseTranslation)
    {
        try {
            $this->persist($request, $hadithVerseTranslation);
            return to_route('admin.hadith-verse-translations.index', [$request->hadith_verse_id])
                ->with('success', 'Verse translation updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-verse-translations.index', [$request->hadith_verse_id, $hadithVerseTranslation->id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->HadithVerseTranslationRepository->dataTable($request->verse_id);
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->HadithVerseTranslationRepository->status($id);
            return response()->json(['message' => 'Hadith translation status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(HadithVerseTranslationStoreRequest $request, ?HadithVerseTranslation $hadithVerseTranslation = null): HadithVerseTranslation
    {
        $data               = $request->validated();
        $data['created_by'] = Auth::user()->id;

        return $this->HadithVerseTranslationRepository->updateOrCreate($data, $hadithVerseTranslation);
    }
}
