<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HadithBook;
use App\Repository\Hadith\HadithBookInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class HadithBookController extends Controller implements HasMiddleware
{
    public function __construct(protected HadithBookInterface $HadithBookRepository)
    {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view hadith-books'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('update hadith-book'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active hadith-book'), only: ['status']),
        ];
    }

    public function index()
    {
        return view('admin.hadith.book');
    }

    public function update(Request $request, HadithBook $hadithBook)
    {
        $request->validate(['name' => 'required|string|max:255', 'writer' => 'required|string|max:255']);

        try {
            $this->HadithBookRepository->update($request->only(['name', 'writer']), $hadithBook);
            return response()->json(['message' => 'Hadith book updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dataTable()
    {
        $results = $this->HadithBookRepository->dataTable();
        return DataTables::of($results)->make(true);
    }

    public function status(string $id)
    {
        try {
            $this->HadithBookRepository->status($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
