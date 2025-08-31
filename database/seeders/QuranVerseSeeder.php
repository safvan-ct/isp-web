<?php
namespace Database\Seeders;

use App\Models\QuranVerse;
use App\Models\QuranVerseTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Normalizer;

class QuranVerseSeeder extends Seeder
{
    public function run(): void
    {
        if (QuranVerse::exists()) {
            $this->command->info('Quran verses already seeded.');
            return;
        }

        $this->command->info('Fetching Quran verses...');

        try {
            // Fetch Arabic
            $arabic    = Http::get('https://api.alquran.cloud/v1/quran/quran-uthmani')->json('data.surahs');
            $english   = Http::get('https://api.alquran.cloud/v1/quran/en.asad')->json('data.surahs');
            $malayalam = Http::get('https://api.alquran.cloud/v1/quran/ml.abdulhameed')->json('data.surahs');
            $hindi     = Http::get('https://api.alquran.cloud/v1/quran/hi.hindi')->json('data.surahs');

            $verses       = [];
            $translations = [];
            $now          = now();

            foreach ($arabic as $surahIndex => $surah) {
                $ayahs = $surah['ayahs'];
                foreach ($ayahs as $i => $ayah) {
                    $verseId = $ayah['number'];

                    $cleanedText = $ayah['text'];
                    if ($surah['number'] > 1 && $ayah['numberInSurah'] == 1) {
                        $bismillahOriginal   = 'بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ';
                        $normalizedAyah      = preg_replace('/[\p{Mn}]/u', '', Normalizer::normalize($ayah['text'], Normalizer::FORM_D));
                        $normalizedBismillah = preg_replace('/[\p{Mn}]/u', '', Normalizer::normalize($bismillahOriginal, Normalizer::FORM_D));

                        if (mb_substr($normalizedAyah, 0, mb_strlen($normalizedBismillah)) === $normalizedBismillah) {
                            $cleanedText = trim(mb_substr($ayah['text'], mb_strlen($bismillahOriginal)));
                        }
                    }

                    $verses[] = [
                        'id'                => $verseId,
                        'quran_chapter_id'  => $surah['number'],
                        'number_in_chapter' => $ayah['numberInSurah'],
                        'text'              => $cleanedText,
                        'juz'               => $ayah['juz'],
                        'manzil'            => $ayah['manzil'],
                        'ruku'              => $ayah['ruku'],
                        'hizb_quarter'      => $ayah['hizbQuarter'],
                        'sajda'             => is_array($ayah['sajda']) ? ($ayah['sajda']['obligatory'] ? 2 : 1) : ($ayah['sajda'] ? 1 : 0),
                        'is_active'         => true,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    // English
                    $textEn         = Arr::get($english, "$surahIndex.ayahs.$i.text");
                    $translations[] = [
                        'quran_chapter_id'  => $surah['number'],
                        'quran_verse_id'    => $verseId,
                        'number_in_chapter' => $ayah['numberInSurah'],
                        'lang'              => 'en',
                        'text'              => $textEn,
                        'created_by'        => 1,
                        'is_active'         => true,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    // Malayalam
                    $textMl         = Arr::get($malayalam, "$surahIndex.ayahs.$i.text");
                    $translations[] = [
                        'quran_chapter_id'  => $surah['number'],
                        'quran_verse_id'    => $verseId,
                        'number_in_chapter' => $ayah['numberInSurah'],
                        'lang'              => 'ml',
                        'text'              => $textMl,
                        'created_by'        => 1,
                        'is_active'         => true,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    // Hindi
                    $textHi         = Arr::get($hindi, "$surahIndex.ayahs.$i.text");
                    $translations[] = [
                        'quran_chapter_id'  => $surah['number'],
                        'quran_verse_id'    => $verseId,
                        'number_in_chapter' => $ayah['numberInSurah'],
                        'lang'              => 'hi',
                        'text'              => $textHi,
                        'created_by'        => 1,
                        'is_active'         => true,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    if (count($verses) === 200) {
                        DB::transaction(function () use ($verses, $translations) {
                            QuranVerse::insert($verses);

                            collect($translations)->chunk(200)->each(function ($chunk) {
                                QuranVerseTranslation::insert($chunk->toArray());
                            });
                        });

                        $verses       = [];
                        $translations = [];
                        gc_collect_cycles();
                    }
                }
            }

            if (! empty($verses)) {
                DB::transaction(function () use ($verses, $translations) {
                    QuranVerse::insert($verses);
                    QuranVerseTranslation::insert($translations);
                    gc_collect_cycles();
                });
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to fetch Quran verses: ' . $e->getMessage());
            return;
        }

        $this->command->info('Quran verses and translations seeded successfully.');
    }
}
