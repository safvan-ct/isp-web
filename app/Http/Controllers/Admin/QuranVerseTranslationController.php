<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranVerseTranslation;
use App\Repository\Quran\QuranVerseTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class QuranVerseTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected QuranVerseTranslationInterface $QuranVerseTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('update quran-verse-translation'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active quran-verse-translation'), only: ['status']),
        ];
    }

    public function update(Request $request, QuranVerseTranslation $quranVerseTranslation)
    {
        $request->validate(['text' => 'required']);

        try {
            $this->QuranVerseTranslationRepository->update($request->only(['text']), $quranVerseTranslation);
            return response()->json(['message' => 'Verse translation updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function status(string $id)
    {
        try {
            $this->QuranVerseTranslationRepository->status($id);
            return response()->json(['message' => 'Verse status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
