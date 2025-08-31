<?php
namespace App\Repository\Quran;

use App\Models\QuranChapterTranslation;

interface QuranChapterTranslationInterface
{
    public function getById($id);

    public function dataTable($chapterId);

    public function updateOrCreate(array $data, ?QuranChapterTranslation $quranChapterTranslation = null);

    public function status($id);

    public function getChapters(?int $chapterId, string $lang = 'en');
}
