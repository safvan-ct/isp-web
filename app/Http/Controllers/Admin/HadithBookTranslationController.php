<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hadith\HadithBookTranslationStoreRequest;
use App\Models\HadithBookTranslation;
use App\Repository\Hadith\HadithBookInterface as HadithHadithBookInterface;
use App\Repository\Hadith\HadithBookTranslationInterface as HadithHadithBookTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithBookTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected HadithHadithBookInterface $HadithBookRepository,
        protected HadithHadithBookTranslationInterface $HadithBookTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-book-translations'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store hadith-book-translation'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update hadith-book-translation'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-book-translation'), only: ['status']),
        ];
    }

    public function index($bookId, $translationId = null)
    {
        $book        = $this->HadithBookRepository->getById($bookId);
        $translation = $this->HadithBookTranslationRepository->getById($translationId);

        return view('admin.hadith.book-translation', compact('book', 'translation'));
    }

    public function store(HadithBookTranslationStoreRequest $request)
    {
        try {
            $this->persist($request);
            return redirect()->route('admin.hadith-book-translations.index', [$request->hadith_book_id])
                ->with('success', 'Book translation created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-book-translations.index', [$request->hadith_book_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(HadithBookTranslationStoreRequest $request, HadithBookTranslation $hadithBookTranslation)
    {
        try {
            $this->persist($request, $hadithBookTranslation);
            return to_route('admin.hadith-book-translations.index', [$request->hadith_book_id])
                ->with('success', 'Book translation updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.hadith-book-translations.index', [$request->hadith_book_id, $request->id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->HadithBookTranslationRepository->dataTable($request->book_id);
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->HadithBookTranslationRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(HadithBookTranslationStoreRequest $request, ?HadithBookTranslation $hadithBookTranslation = null): HadithBookTranslation
    {
        $data               = $request->validated();
        $data['created_by'] = Auth::user()->id;

        return $this->HadithBookTranslationRepository->updateOrCreate($data, $hadithBookTranslation);
    }
}
