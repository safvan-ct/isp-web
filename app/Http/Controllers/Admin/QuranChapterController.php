<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranChapter;
use App\Repository\Quran\QuranChapterInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class QuranChapterController extends Controller implements HasMiddleware
{
    public function __construct(
        protected QuranChapterInterface $QuranChapterRepository,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view quran-chapters'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('update quran-chapter'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active quran-chapter'), only: ['status']),
        ];
    }

    public function index()
    {
        return view('admin.quran.chapters');
    }

    public function update(Request $request, QuranChapter $quranChapter)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $this->QuranChapterRepository->update($request->only(['name']), $quranChapter);
            return response()->json(['message' => 'Chpter updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dataTable()
    {
        $results = $this->QuranChapterRepository->dataTable();
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->QuranChapterRepository->status($id);
            return response()->json(['message' => 'Chpter status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
