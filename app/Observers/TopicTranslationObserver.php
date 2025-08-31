<?php
namespace App\Observers;

use App\Models\TopicTranslation;
use Illuminate\Support\Facades\Cache;

class TopicTranslationObserver
{
    public function created(TopicTranslation $topicTranslation)
    {
        $this->clearMenuCache($topicTranslation);
    }

    public function updated(TopicTranslation $topicTranslation)
    {
        $this->clearMenuCache($topicTranslation);
    }

    public function deleted(TopicTranslation $topicTranslation)
    {
        $this->clearMenuCache($topicTranslation);
    }

    protected function clearMenuCache(TopicTranslation $topicTranslation)
    {
        $topic = $topicTranslation->topic()->withoutGlobalScopes()->first();

        if ($topic && $topic->type === 'menu') {
            foreach (config('app.languages') as $lang => $name) {
                Cache::forget($lang . '_primary_menus');
            }
        }
    }
}
