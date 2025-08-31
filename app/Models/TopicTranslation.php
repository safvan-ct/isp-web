<?php
namespace App\Models;

use App\Observers\TopicTranslationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TopicTranslationObserver::class])]

class TopicTranslation extends Model
{
    protected $fillable = ['topic_id', 'lang', 'title', 'sub_title', 'content', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLang($query, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        return $query->where('lang', $lang);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
