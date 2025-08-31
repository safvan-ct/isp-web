<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithVerseTranslation extends Model
{
    use LogsActivity;

    protected $fillable = ['hadith_verse_id', 'lang', 'heading', 'text', 'created_by', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'heading', 'text'])
            ->useLogName('hadith_verse_translations')
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
