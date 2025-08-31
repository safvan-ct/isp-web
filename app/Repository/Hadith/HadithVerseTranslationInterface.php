<?php
namespace App\Repository\Hadith;

use App\Models\HadithVerseTranslation;

interface HadithVerseTranslationInterface
{
    public function getById($id);

    public function dataTable($verseId);

    public function updateOrCreate(array $data, ?HadithVerseTranslation $hadithVerseTranslation = null);

    public function status($id);
}
