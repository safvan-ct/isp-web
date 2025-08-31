<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class QuranChapterTranslation extends Model
{
    use LogsActivity;

    protected $fillable = ['quran_chapter_id', 'lang', 'name', 'translation', 'created_by', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'translation', 'is_active'])
            ->useLogName('quran_chapter_translations')
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
