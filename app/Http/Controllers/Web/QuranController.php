<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repository\Quran\QuranChapterInterface;

class QuranController extends Controller
{
    protected $webVersion;

    public function __construct(
        protected QuranChapterInterface $quranChapterRepository,
    ) {
        $this->webVersion = config("constants.web_version");
    }

    public function quran()
    {
        $juzs = [
            [
                "number" => 1,
                "title"  => "Alif-Lam-Mīm",
                "range"  => "1:1 - 2:141",
            ],
            [
                "number" => 2,
                "title"  => "Sayaqūlu",
                "range"  => "2:142 - 2:252",
            ],
            [
                "number" => 3,
                "title"  => "Tilkal-Rusul",
                "range"  => "2:253 - 3:92",
            ],
            [
                "number" => 4,
                "title"  => "Lan tanalū al-birra",
                "range"  => "3:93 - 4:23",
            ],
            [
                "number" => 5,
                "title"  => "Wal-muḥṣanāt",
                "range"  => "4:24 - 4:147",
            ],
            [
                "number" => 6,
                "title"  => "Lā yuḥibbu-llāh",
                "range"  => "4:148 - 5:81",
            ],
            [
                "number" => 7,
                "title"  => "Wa-iżā samiʿū",
                "range"  => "5:82 - 6:110",
            ],
            [
                "number" => 8,
                "title"  => "Wa-law annānā",
                "range"  => "6:111 - 7:87",
            ],
            [
                "number" => 9,
                "title"  => "Qāla al-malā’u",
                "range"  => "7:88 - 8:40",
            ],
            [
                "number" => 10,
                "title"  => "Wa aʿlamū",
                "range"  => "8:41 - 9:92",
            ],
            [
                "number" => 11,
                "title"  => "Yaʿtadhirūn",
                "range"  => "9:93 - 11:5",
            ],
            [
                "number" => 12,
                "title"  => "Wa-mā min dābbatin",
                "range"  => "11:6 - 12:52",
            ],
            [
                "number" => 13,
                "title"  => "Wa mā ubbirū",
                "range"  => "12:53 - 14:52",
            ],
            [
                "number" => 14,
                "title"  => "Rubamā",
                "range"  => "15:1 - 16:128",
            ],
            [
                "number" => 15,
                "title"  => "Subḥan-alladhī",
                "range"  => "17:1 - 18:74",
            ],
            [
                "number" => 16,
                "title"  => "Qāla alam",
                "range"  => "18:75 - 20:135",
            ],
            [
                "number" => 17,
                "title"  => "Iqtaraba li-n-nāsi",
                "range"  => "21:1 - 22:78",
            ],
            [
                "number" => 18,
                "title"  => "Qadd aflaha",
                "range"  => "23:1 - 25:20",
            ],
            [
                "number" => 19,
                "title"  => "Wa qāla",
                "range"  => "25:21 - 27:55",
            ],
            [
                "number" => 20,
                "title"  => "Aman khalaqa",
                "range"  => "27:56 - 29:45",
            ],
            [
                "number" => 21,
                "title"  => "Utlu mā ūḥiya",
                "range"  => "29:46 - 33:30",
            ],
            [
                "number" => 22,
                "title"  => "Wa-man yaqnut",
                "range"  => "33:31 - 36:27",
            ],
            [
                "number" => 23,
                "title"  => "Wa mā li",
                "range"  => "36:28 - 39:31",
            ],
            [
                "number" => 24,
                "title"  => "Fa-man aẓlama",
                "range"  => "39:32 - 41:46",
            ],
            [
                "number" => 25,
                "title"  => "Ilayhi yuraddū",
                "range"  => "41:47 - 45:37",
            ],
            [
                "number" => 26,
                "title"  => "Ḥā-Mīm",
                "range"  => "46:1 - 51:30",
            ],
            [
                "number" => 27,
                "title"  => "Qāla famā khaṭbukum",
                "range"  => "51:31 - 57:29",
            ],
            [
                "number" => 28,
                "title"  => "Qadd samiʿa-llāh",
                "range"  => "58:1 - 66:12",
            ],
            [
                "number" => 29,
                "title"  => "Tabāraka-lladhī",
                "range"  => "67:1 - 77:50",
            ],
            [
                "number" => 30,
                "title"  => "ʿAmmā",
                "range"  => "78:1 - 114:6",
            ],
        ];
        $chapters = $this->quranChapterRepository->getWithTranslations();
        return view("web.quran-chapters", compact("chapters", "juzs"));
    }

    public function quranChapter($id)
    {
        $chapter = $this->quranChapterRepository->getWithVerses($id);
        if (! $chapter) {
            abort(404);
        }

        return view("web.quran-verses", compact("chapter"));
    }
}
