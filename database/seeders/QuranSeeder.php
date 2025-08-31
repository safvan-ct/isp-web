<?php
namespace Database\Seeders;

use App\Models\QuranChapter;
use App\Models\QuranChapterTranslation;
use App\Services\ApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class QuranSeeder extends Seeder
{
    public function __construct(protected ApiService $apiService)
    {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent duplicate seeding
        if (QuranChapter::exists()) {
            $this->command->info('Quran chapters have already been seeded.');
            return;
        }

        try {
            $response = $this->apiService->get("https://api.alquran.cloud/v1/surah");

            if ($response['status'] !== 200) {
                $this->command->error('Failed to fetch Quran chapters: Invalid status');
                return;
            }

            $data = Arr::get($response, 'result.data', []);
            if (empty($data)) {
                $this->command->error('Failed to fetch Quran chapters: No data in response.');
                return;
            }

            $now          = now();
            $chapters     = [];
            $translations = [];

            foreach ($data as $surah) {
                $chapters[] = [
                    'id'               => $surah['number'],
                    'name'             => str_replace('سُورَةُ ', '', $surah['name']),
                    'no_of_verses'     => $surah['numberOfAyahs'],
                    'revelation_place' => $surah['revelationType'],
                    'is_active'        => true,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];

                $translations[] = [
                    'quran_chapter_id' => $surah['number'],
                    'lang'             => 'en',
                    'name'             => $surah['englishName'],
                    'translation'      => $surah['englishNameTranslation'],
                    'created_by'       => 1, // Seeder fallback user ID
                    'is_active'        => true,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }

            DB::transaction(function () use ($chapters, $translations) {
                QuranChapter::insert($chapters);
                QuranChapterTranslation::insert($translations);
            });

            $ml = [
                "അല്‍-ഫാത്തിഹ", "അല്‍-ബഖറ", "ആല്‍-ഇംറാന്‍", "അന്‍-നിസാ", "അല്‍-മായിദ", "അല്‍-അന്‍ആം", "അല്‍-അഅ്‌റാഫ്",
                "അല്‍-അന്‍ഫാല്‍", "അത്-തൗബ", "യൂനുസ്", "ഹൂദ്", "യൂസുഫ്", "അര്‍-റഅ്ദ്", "ഇബ്രാഹീം", "അല്‍-ഹിജ്ര്‍", "അന്‍-നഹ്‌ല്‍",
                "അല്‍-ഇസ്റാ", "അല്‍-കഹ്‌ഫ്", "മര്‍യ്യം", "ത്വാ ഹാ", "അല്‍-അന്‍ബിയാ", "അല്‍-ഹജ്", "അല്‍-മുഅ്‌മിനൂന്‍", "അന്‍-നൂര്‍",
                "അല്‍-ഫുര്‍ഖാന്‍", "അശ്-ശുഅറാ", "അന്‍-നമ്‌ല്‍", "അല്‍-ഖസസ്", "അല്‍-അങ്കബൂത്", "അര്‍-റൂം", "ലുഖ്‌മാന്‍", "അസ്-സജ്ദ",
                "അല്‍-അഹ്‌സാബ്", "സബഅ്", "ഫാത്തിര്‍", "യാ-സീന്‍", "അസ്-സാഫ്‌ഫാത്", "സാദ്", "അസ്-സുമര്‍", "ഘാഫിര്‍", "ഫുസ്സിലത്",
                "അശ്-ഷൂറാ", "അല്‍-സുഖ്‌റുഫ്", "അദ്-ദുഖാന്‍", "അല്‍-ജാസിയ", "അല്‍-അഹ്‌ഖാഫ്", "മുഹമ്മദ്", "അല്‍-ഫത്‌ഹ്",
                "അല്‍-ഹുജുറാത്ത്", "ഖാഫ്", "അദ്-ധാരിയാത്", "അത്-തൂര്‍", "അന്‍-നജ്മ്", "അല്‍-ഖമര്‍", "അര്‍-റഹ്്മാന്‍", "അല്‍-വാഖിഅ",
                "അല്‍-ഹദീദ്", "അല്‍-മുജാദില", "അല്‍-ഹശ്‌ര്‍", "അല്‍-മുംതഹിന", "അസ്-സഫ്‌ഫ്", "അല്‍-ജുമുഅ", "അല്‍-മുനാഫിഖൂന്‍",
                "അത്-തഘാബുന്‍", "അത്-തലാഖ്", "അത്-തഹ്‌രീം", "അല്‍-മുല്‍ക്ക്", "അല്‍-ഖലം", "അല്‍-ഹാഖ്‌ഖ", "അല്‍-മഅാരിജ്", "നൂഹ്",
                "അല്‍-ജിന്ന്", "അല്‍-മുജമ്മില്‍", "അല്‍-മുദ്ദസ്സിര്‍", "അല്‍-ഖിയാമ", "അല്‍-ഇന്‍സാന്‍", "അല്‍-മുര്‍സലാത്ത്", "അന്‍-നബ",
                "അന്‍-നാസിഅത്", "അബസ", "അത്-തക്വീര്‍", "അല്‍-ഇന്‍ഫിതാര്‍", "അല്‍-മുതഫ്ഫിഫീന്‍", "അല്‍-ഇന്‍ഷിഖാഖ്", "അല്‍-ബുരൂജ്",
                "അത്-താരിഖ്", "അല്‍-അഅ്‌ലാ", "അല്‍-ഘാഷിയ", "അല്‍-ഫജ്ര്‍", "അല്‍-ബലദ്", "അശ്-ഷംസ്", "അല്‍-ലൈല്‍", "അദ്-ദുഹാ",
                "അല്‍-ഇന്‍ഷിറാഹ്", "അത്-തീന്‍", "അല്‍-അലഖ്", "അല്‍-ഖദര്‍", "അല്‍-ബയ്യിന", "അസ്-സല്‍സല", "അല്‍-ആദിയാത്ത്",
                "അല്‍-ഖാരിഅ", "അത്-തകാസുര്‍", "അല്‍-അസ്ര്‍", "അല്‍-ഹുമഴ", "അല്‍-ഫീല്‍", "ഖുറൈശ്", "അല്‍-മാഊന്‍", "അല്‍-കൗഥര്‍",
                "അല്‍-കാഫിറൂന്‍", "അന്‍-നസ്ര്‍", "അല്‍-മസദ്", "അല്‍-ഇഖ്‌ലാസ്", "അല്‍-ഫലഖ്", "അന്‍-നാസ്",
            ];

            $translations = [];
            foreach ($ml as $key => $value) {
                $translations[] = [
                    'quran_chapter_id' => $key + 1,
                    'lang'             => 'ml',
                    'name'             => $value,
                    'translation'      => '',
                    'created_by'       => 1, // Seeder fallback user ID
                    'is_active'        => true,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
            DB::transaction(function () use ($translations) {
                QuranChapterTranslation::insert($translations);
            });

            $this->command->info('Quran chapters seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Failed to fetch Quran chapters: ' . $e->getMessage());
            return;
        }
    }
}
