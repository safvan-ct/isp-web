<?php
namespace Database\Seeders;

use App\Models\HadithBook;
use App\Models\HadithVerse;
use App\Models\HadithVerseTranslation;
use App\Services\ApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HadithVerseSeeder extends Seeder
{
    public function __construct(protected ApiService $apiService)
    {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        set_time_limit(0);             // safer than -1 in some environments
        ini_set('memory_limit', '-1'); // Unlimited memory

        $book = HadithBook::with(['chapters:id,hadith_book_id', 'verses:id,hadith_book_id'])
            ->select(['id', 'slug'])
            ->whereHas('chapters')
            ->whereDoesntHave('verses')
            ->orderBy('id')
            ->first();
        $apiKey  = config('services.hadith.api_key');
        $baseUrl = config('services.hadith.hadith');
        $now     = now();
        $urlBase = str_replace("{api_key}", $apiKey, $baseUrl);

        if (! $book) {
            $this->command->info("Hadith verse seeding completed at: {$now->toDateTimeString()}");
            return;
        }

        $this->command->info("Hadith verse seeding started at: {$now->toDateTimeString()}");

        try {
            // foreach ($book->chapters as $chapter) {
            //     $url     = "{$url}&book={$chapter->book->slug}&chapter={$chapter->id}&paginate=300";
            // }

            if (! $book->chapters->count()) {
                $this->command->warn("No chapters for book: {$book->slug}. Skipping.");
                return;
            }

            if ($book->verses->count()) {
                $this->command->warn("Verses already exist for book: {$book->slug}. Skipping.");
                return;
            }

            $this->command->info("Seeding book: {$book->slug}");

            $url       = "{$urlBase}&book={$book->slug}&paginate=500&page=1";
            $firstPage = $this->apiService->get("{$url}&page=1");
            if (Arr::get($firstPage, 'status') !== 200) {
                $this->command->error("Failed: {$book->slug}");
                return;
            }

            $totalPages = Arr::get($firstPage, 'result.hadiths.last_page', 0);

            for ($page = 1; $page <= $totalPages; $page++) {
                $url      = "{$url}&page={$page}";
                $response = $this->apiService->get($url);
                if (Arr::get($response, 'status') !== 200) {
                    $this->command->error("Error on page {$page}: " . ($response['message'] ?? ''));
                    continue;
                }

                $data         = Arr::get($response, 'result.hadiths.data', []);
                $hadiths      = [];
                $translations = [];

                foreach ($data as $hadith) {
                    $hadiths[] = [
                        'id'                => $hadith['id'],
                        'hadith_book_id'    => $hadith['book']['id'],
                        'hadith_chapter_id' => $hadith['chapter']['id'],
                        'chapter_number'    => $hadith['chapter']['chapterNumber'],
                        'hadith_number'     => (int) $hadith['hadithNumber'],
                        'heading'           => $hadith['headingArabic'],
                        'text'              => $hadith['hadithArabic'],
                        'volume'            => $hadith['volume'],
                        'status'            => $hadith['status'],
                        'is_active'         => 1,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    $translations[] = [
                        'hadith_verse_id' => $hadith['id'],
                        'lang'            => 'en',
                        'heading'         => $hadith['headingEnglish'],
                        'text'            => $hadith['hadithEnglish'],
                        'is_active'       => 1,
                        'created_by'      => 1,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }

                DB::transaction(function () use ($hadiths, $translations) {
                    HadithVerse::insert($hadiths);
                    HadithVerseTranslation::insert($translations);
                });

                $this->command->info("Inserted page {$page} of {$totalPages} for book: {$book->slug}");

                unset($hadiths, $translations);
                gc_collect_cycles(); // clear memory
            }

            $this->command->info("Hadith verse seeding completed at: " . date('Y-m-d H:i:s'));
            $books = HadithBook::select(['id', 'slug'])
                ->whereHas('chapters')
                ->whereDoesntHave('verses')
                ->count();

            if ($books > 0) {
                $this->command->info("Seeding remaining books: {$books}");
                $this->command->call('db:seed', ['--class' => HadithVerseSeeder::class]);
            }

        } catch (\Exception $e) {
            $this->command->error('Error fetching hadiths: ' . $e->getMessage());
            Log::error('Error fetching hadiths: ' . $e->getMessage());
            return;
        }
    }
}
