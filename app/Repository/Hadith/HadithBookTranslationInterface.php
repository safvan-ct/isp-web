<?php
namespace App\Repository\Hadith;

use App\Models\HadithBookTranslation;

interface HadithBookTranslationInterface
{
    public function getById($id);

    public function dataTable($bookId);

    public function updateOrCreate(array $data, ?HadithBookTranslation $hadithBookTranslation = null);

    public function status($id);

    public function getBooks(?string $name, string $lang = 'en');
}
