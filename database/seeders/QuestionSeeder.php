<?php
namespace Database\Seeders;

use App\Models\Topic;
use App\Models\TopicTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $this->command->info("Question seeding started at: {$now->toDateTimeString()}");

        $modules = Topic::where('type', 'module')->get();

        try {
            foreach ($modules as $module) {
                if (Topic::where('type', 'question')->where('parent_id', $module->id)->exists()) {
                    continue;
                }
                $path      = database_path("json/questions/{$module->slug}.json");
                $questions = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

                foreach ($questions as $key => $question) {
                    $item = Topic::create([
                        'parent_id'  => $module->id,
                        'slug'       => Str::slug($question['title']),
                        'type'       => 'question',
                        'is_primary' => 0,
                        'position'   => $key + 1,
                    ]);

                    $translations = [];
                    foreach ($question['translations'] as $translation) {
                        $translations[] = [
                            'topic_id'   => $item->id,
                            'lang'       => $translation['lang'],
                            'title'      => $translation['title'],
                            'sub_title'  => $translation['sub_title'] ?? null,
                            'content'    => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    TopicTranslation::insert($translations);
                }
            }

            $this->command->info('Question seeding completed at: ' . now()->toDateTimeString());
        } catch (\Throwable $e) {
            $this->command->error('Failed to seed questions: ' . $e->getMessage());
        }
    }
}
