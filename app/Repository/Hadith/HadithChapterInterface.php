<?php
namespace App\Repository\Hadith;

use App\Models\HadithChapter;

interface HadithChapterInterface
{
    public function getById($id);

    public function dataTable($bookId);

    public function status($id);

    public function update(array $data, HadithChapter $hadithChapter);

    public function getwithAll($id = null, $hadithNumber = null);

    public function getChpaters($bookId, $name = null);
}
