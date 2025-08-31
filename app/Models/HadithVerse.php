<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithVerse extends Model
{
    use LogsActivity;

    protected $fillable = ['hadith_book_id', 'hadith_chapter_id', 'chapter_number', 'hadith_number', 'heading', 'text', 'volume', 'status', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'text', 'heading'])
            ->useLogName('hadith_verse')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
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
        return $this->hasMany(HadithVerseTranslation::class)
            ->select('id', 'hadith_verse_id', 'heading', 'text')
            ->active()
            ->lang('en');
    }

    // --------------------
    // Chapter
    // --------------------
    public function chapter()
    {
        return $this->belongsTo(HadithChapter::class, 'hadith_chapter_id');
    }

    // --------------------
    // Book
    // --------------------
    public function book()
    {
        return $this->belongsTo(HadithBook::class, 'hadith_book_id');
    }

    // --------------------
    // Likes
    // --------------------
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
