<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(QuranSeeder::class);
        $this->call(QuranVerseSeeder::class);

        $this->call(HadithBookSeeder::class);
        $this->call(HadithChapterSeeder::class);
        $this->call(HadithVerseSeeder::class);
    }
}
