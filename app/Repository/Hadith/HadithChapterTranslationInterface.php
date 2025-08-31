<?php
namespace App\Repository\Hadith;

use App\Models\HadithChapterTranslation;

interface HadithChapterTranslationInterface
{
    public function getById($id);

    public function dataTable($chapterId);

    public function updateOrCreate(array $data, ?HadithChapterTranslation $hadithChapterTranslation = null);

    public function status($id);
}
