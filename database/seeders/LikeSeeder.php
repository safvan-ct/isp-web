<?php
namespace Database\Seeders;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now        = now();
        $likedItems = [];
        $userId     = User::where('email', 'safvan@email.com')->first()->id;

        for ($i = 1; $i <= 6000; $i++) {
            $likedItems[] = [
                'user_id'       => $userId,
                'likeable_id'   => $i,
                'likeable_type' => 'App\Models\QuranVerse',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        for ($i = 1; $i <= 10000; $i++) {
            $likedItems[] = [
                'user_id'       => $userId,
                'likeable_id'   => $i,
                'likeable_type' => 'App\Models\HadithVerse',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        for ($i = 22; $i <= 24; $i++) {
            $likedItems[] = [
                'user_id'       => $userId,
                'likeable_id'   => $i,
                'likeable_type' => 'App\Models\Topic',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        foreach (array_chunk($likedItems, 1000) as $chunk) {
            Like::insert($chunk);
        }
    }
}
