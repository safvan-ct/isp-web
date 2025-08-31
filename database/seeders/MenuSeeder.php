<?php
namespace Database\Seeders;

use App\Models\Topic;
use App\Models\TopicHadithVerse;
use App\Models\TopicQuranVerse;
use App\Models\TopicTranslation;
use App\Models\TopicVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TopicVideo::truncate();
        TopicHadithVerse::truncate();
        TopicQuranVerse::truncate();
        TopicTranslation::truncate();
        Topic::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();
        $this->command->info("Menu seeding started at: {$now->toDateTimeString()}");

        if (Topic::where('type', 'menu')->exists()) {
            $this->command->warn('Modules already exist.');
            return;
        }

        try {
            $path  = database_path("json/menus.json");
            $menus = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

            foreach ($menus as $key => $menu) {
                $item = Topic::create([
                    'slug'       => $menu['slug'],
                    'type'       => 'menu',
                    'is_primary' => $menu['is_primary'] ?? 0,
                    'position'   => $key + 1,
                ]);

                $translations = [];
                foreach ($menu['translations'] as $translation) {
                    $translations[] = [
                        'topic_id'   => $item->id,
                        'lang'       => $translation['lang'],
                        'title'      => $translation['title'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                TopicTranslation::insert($translations);
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to seed menus: ' . $e->getMessage());
            return;
        }

        $this->command->info("Menu seeding completed at: " . date('Y-m-d H:i:s'));

        $this->call(ModuleSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(AnswerSeeder::class);
    }
}
