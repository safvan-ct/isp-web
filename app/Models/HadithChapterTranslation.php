<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithChapterTranslation extends Model
{
    use LogsActivity;

    protected $fillable = ['hadith_chapter_id', 'lang', 'name', 'created_by', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'name'])
            ->useLogName('hadith_chapter_translations')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
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
