<?php
namespace Database\Seeders;

use App\Models\Topic;
use App\Models\TopicTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $this->command->info("Module seeding started at: {$now->toDateTimeString()}");

        if (Topic::where('type', 'module')->exists()) {
            $this->command->warn('Modules already exist.');
            return;
        }

        $menus = Topic::where('type', 'menu')->get();

        try {
            foreach ($menus as $menu) {
                if (Topic::where('type', 'module')->where('parent_id', $menu->id)->exists()) {
                    continue;
                }

                $path    = database_path("json/modules/{$menu->slug}.json");
                $modules = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

                foreach ($modules as $key => $module) {
                    DB::transaction(function () use ($module, $key, $now, $menu) {
                        $item = Topic::create([
                            'parent_id'  => $menu->id,
                            'slug'       => Str::slug($module['title']),
                            'type'       => 'module',
                            'is_primary' => $module['is_primary'] ?? 0,
                            'position'   => $key + 1,
                        ]);

                        $translations = [];
                        foreach ($module['translations'] as $translation) {
                            $translations[] = [
                                'topic_id'   => $item->id,
                                'lang'       => $translation['lang'],
                                'title'      => $translation['title'],
                                'sub_title'  => $translation['sub_title'] ?? null,
                                'content'    => $translation['desc'] ?? null,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }

                        TopicTranslation::insert($translations);
                    });
                }
            }

            $this->command->info('Modules seeding completed at: ' . now()->toDateTimeString());
        } catch (\Throwable $e) {
            $this->command->error('Failed to seed modules: ' . $e->getMessage());
        }
    }
}
