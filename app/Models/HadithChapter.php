<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithChapter extends Model
{
    use LogsActivity;

    protected $fillable = ['hadith_book_id', 'chapter_number', 'name', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'name'])
            ->useLogName('hadith_chapters')
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
        return $this->hasMany(HadithChapterTranslation::class)
            ->select('id', 'hadith_chapter_id', 'name')
            ->active()
            ->lang();
    }

    // --------------------
    // Book
    // --------------------
    public function book()
    {
        return $this->belongsTo(HadithBook::class, 'hadith_book_id');
    }

    // --------------------
    // Verses
    // --------------------
    public function verses()
    {
        return $this->hasMany(HadithVerse::class);
    }
}
