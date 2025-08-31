<?php
namespace App\Models;

use App\Observers\TopicObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TopicObserver::class])]

class Topic extends Model
{
    protected $fillable = ['parent_id', 'slug', 'type', 'position', 'is_primary', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // --------------------
    // Translations
    // --------------------
    public function getTranslationAttribute()
    {
        return $this->translations->first();
    }

    public function translations()
    {
        return $this->hasMany(TopicTranslation::class)
            ->select('id', 'topic_id', 'title', 'sub_title', 'content')
            ->active()
            ->lang();
    }

    // --------------------
    // Parent topic
    // --------------------
    public function parent()
    {
        return $this->belongsTo(Topic::class, 'parent_id')
            ->select('id', 'slug', 'parent_id', 'position')
            ->active();
    }

    // --------------------
    // Children
    // --------------------
    public function children()
    {
        return $this->hasMany(Topic::class, 'parent_id')
            ->select('id', 'slug', 'parent_id', 'position')
            ->orderBy('position')
            ->active();
    }

    // --------------------
    // Quran verses nested
    // --------------------
    public function quranVerses()
    {
        return $this->hasMany(TopicQuranVerse::class, 'topic_id')
            ->with('quran.chapter.translations')
            ->orderBy('position');
    }

    // --------------------
    // Hadith verses nested
    // --------------------
    public function hadithVerses()
    {
        return $this->hasMany(TopicHadithVerse::class, 'topic_id')
            ->with('hadith.chapter.book.translations')
            ->orderBy('position');
    }

    // --------------------
    // Videos
    // --------------------
    public function videos()
    {
        return $this->hasMany(TopicVideo::class)
            ->orderBy('position');
    }

    // --------------------
    // Likes
    // --------------------
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
