<?php
namespace App\Repository\Quran;

use App\Models\QuranChapter;

interface QuranChapterInterface
{
    public function getById($id);

    public function dataTable();

    public function status($id);

    public function update(array $data, QuranChapter $quranChapter);

    public function getAll();

    public function getWithTranslations();

    public function getWithVerses($id = null);
}
