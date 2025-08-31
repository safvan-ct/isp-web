<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HadithBookTranslation extends Model
{
    use LogsActivity;

    protected $fillable = ['hadith_book_id', 'lang', 'name', 'writer', 'created_by', 'is_active'];

    protected static $recordEvents = ['updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_active', 'name', 'writer'])
            ->useLogName('hadith_book_translations')
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
