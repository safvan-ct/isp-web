<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class QuranVerseTranslation extends Model
{
    use LogsActivity;

    protected $fillable = ['quran_chapter_id', 'quran_verse_id', 'number_in_chapter', 'lang', 'text', 'created_by', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['text', 'is_active'])
            ->useLogName('quran_verse_translations')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLang($query, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        return $query->where('lang', $lang);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
