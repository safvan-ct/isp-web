<?php
namespace App\Repository\Quran;

use App\Models\QuranVerseTranslation;

interface QuranVerseTranslationInterface
{
    public function getById($id);

    public function dataTable($chapterId, $lang);

    public function update(array $data, QuranVerseTranslation $quranVerseTranslation);

    public function status($id);
}
