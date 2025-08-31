<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicHadithVerse extends Model
{
    protected $table = 'topic_hadith';

    protected $fillable = [
        'topic_id',
        'hadith_verse_id',
        'simplified',
        'translation_json',
        'position',
    ];

    protected $casts = [
        'translation_json' => 'array',
    ];

    public function hadith()
    {
        return $this->belongsTo(HadithVerse::class, 'hadith_verse_id');
    }
}
