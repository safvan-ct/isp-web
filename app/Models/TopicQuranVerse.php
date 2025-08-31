<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TopicQuranVerse extends Pivot
{
    protected $table = 'topic_quran_verse';

    protected $fillable = [
        'topic_id',
        'quran_verse_id',
        'simplified',
        'translation_json',
        'position',
    ];

    protected $casts = [
        'translation_json' => 'array',
    ];

    public function quran()
    {
        return $this->belongsTo(QuranVerse::class, 'quran_verse_id');
    }
}
