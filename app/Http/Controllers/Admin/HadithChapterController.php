<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HadithChapter;
use App\Repository\Hadith\HadithBookInterface;
use App\Repository\Hadith\HadithChapterInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithChapterController extends Controller implements HasMiddleware
{
    public function __construct(
        protected HadithBookInterface $HadithBookRepository,
        protected HadithChapterInterface $HadithChapterRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-chapters'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('update hadith-chapter'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-chapter'), only: ['status']),
        ];
    }

    public function index()
    {
        $books = $this->HadithBookRepository->getAll();
        return view('admin.hadith.chapter', compact('books'));
    }

    public function update(Request $request, HadithChapter $hadithChapter)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $this->HadithChapterRepository->update($request->only(['name']), $hadithChapter);
            return response()->json(['message' => 'Hadith chapter updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dataTable(Request $request)
    {
        $results = $this->HadithChapterRepository->dataTable($request->book_id);
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->HadithChapterRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
