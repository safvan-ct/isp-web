<?php
namespace App\Repository\Hadith;

use App\Models\HadithBook;

interface HadithBookInterface
{
    public function getById($id);

    public function dataTable();

    public function status($id);

    public function update(array $data, HadithBook $hadithBook);

    public function getAll();

    public function getWithTranslations();

    public function getWithChapters($id = null);
}
