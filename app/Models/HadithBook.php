<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithBook extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'slug', 'writer', 'writer_death_year', 'chapter_count', 'hadith_count', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'name', 'writer'])
            ->useLogName('hadith_books')
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
        return $this->hasMany(HadithBookTranslation::class)
            ->select('id', 'hadith_book_id', 'name', 'writer')
            ->active()
            ->lang();
    }

    // --------------------
    // Chapters
    // --------------------
    public function chapters()
    {
        return $this->hasMany(HadithChapter::class);
    }

    // --------------------
    // Verses
    // --------------------
    public function verses()
    {
        return $this->hasMany(HadithVerse::class);
    }
}
