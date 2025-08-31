<?php
namespace Database\Seeders;

use App\Models\HadithBook;
use App\Models\HadithVerse;
use App\Models\QuranVerse;
use App\Models\Topic;
use App\Models\TopicHadithVerse;
use App\Models\TopicQuranVerse;
use App\Models\TopicTranslation;
use App\Models\TopicVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $this->command->info("Answer seeding started at: {$now->toDateTimeString()}");

        $questions = Topic::where('type', 'question')->get();

        try {
            foreach ($questions as $question) {
                if (Topic::where('type', 'answer')->where('parent_id', $question->id)->exists()) {
                    continue;
                }
                $path    = database_path("json/answers/{$question->slug}.json");
                $answers = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

                foreach ($answers as $key => $answer) {
                    $item = Topic::create([
                        'parent_id'  => $question->id,
                        'slug'       => Str::slug($answer['title']),
                        'type'       => 'answer',
                        'is_primary' => 0,
                        'position'   => $key + 1,
                    ]);

                    $translations = [];
                    foreach ($answer['translations'] as $translation) {
                        $translations[] = [
                            'topic_id'   => $item->id,
                            'lang'       => $translation['lang'],
                            'title'      => $translation['title'],
                            'sub_title'  => null,
                            'content'    => $translation['desc'] ?? null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    TopicTranslation::insert($translations);

                    if (isset($answer['quran'])) {
                        $quranVerses = [];
                        foreach ($answer['quran'] as $k => $quran) {
                            $verse = QuranVerse::where('number_in_chapter', $quran['number_in_chapter'])
                                ->where('quran_chapter_id', $quran['chapter'])
                                ->first();

                            if ($verse) {
                                $quranVerses[] = [
                                    'topic_id'         => $item->id,
                                    'quran_verse_id'   => $verse->id,
                                    'simplified'       => $quran['simplified'] ?? null,
                                    'translation_json' => isset($quran['translation_json']) ? json_encode(json_decode(str_replace("'", '"', $quran['translation_json']), true), JSON_UNESCAPED_UNICODE) : null,
                                    'position'         => $k + 1,
                                    'created_at'       => $now,
                                    'updated_at'       => $now,
                                ];
                            }
                        }

                        if (count($quranVerses) > 0) {
                            TopicQuranVerse::insert($quranVerses);
                        }
                    }

                    if (isset($answer['hadiths'])) {
                        $hadithsVerses = [];
                        foreach ($answer['hadiths'] as $k => $hadith) {
                            $book = HadithBook::where('slug', $hadith['book_slug'])->first();
                            if (! $book) {
                                continue;
                            }
                            $verse = HadithVerse::where('hadith_book_id', $book->id)
                                ->where('hadith_number', $hadith['hadith_number'])
                                ->first();

                            if ($verse) {
                                $hadithsVerses[] = [
                                    'topic_id'         => $item->id,
                                    'hadith_verse_id'  => $verse->id,
                                    'simplified'       => $hadith['simplified'] ?? null,
                                    'translation_json' => isset($hadith['translation_json']) ? json_encode(json_decode(str_replace("'", '"', $hadith['translation_json']), true), JSON_UNESCAPED_UNICODE) : null,
                                    'position'         => $k + 1,
                                    'created_at'       => $now,
                                    'updated_at'       => $now,
                                ];
                            }
                        }

                        if (count($hadithsVerses) > 0) {
                            TopicHadithVerse::insert($hadithsVerses);
                        }
                    }

                    if (isset($answer['videos'])) {
                        $videos = [];
                        foreach ($answer['videos'] as $k => $video) {
                            $videos[] = [
                                'topic_id'   => $item->id,
                                'video_id'   => $video['video_id'],
                                'title'      => isset($video['title']) ? json_encode(json_decode(str_replace("'", '"', $video['title']), true), JSON_UNESCAPED_UNICODE) : null,
                                'position'   => $k + 1,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }

                        if (count($videos) > 0) {
                            TopicVideo::insert($videos);
                        }
                    }
                }
            }

            $this->command->info('Answer seeding completed at: ' . now()->toDateTimeString());
        } catch (\Throwable $e) {
            $this->command->error('Failed to seed answers: ' . $e->getMessage());
        }
    }
}
