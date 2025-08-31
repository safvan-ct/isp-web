<?php
namespace App\Observers;

use App\Models\Topic;
use Illuminate\Support\Facades\Cache;

class TopicObserver
{
    public function created(Topic $topic)
    {
        $this->clearMenuCache($topic);
    }

    public function updated(Topic $topic)
    {
        $this->clearMenuCache($topic);
    }

    public function deleted(Topic $topic)
    {
        $this->clearMenuCache($topic);
    }

    protected function clearMenuCache(Topic $topic)
    {
        if ($topic->type === 'menu') {
            foreach (config('app.languages') as $lang => $name) {
                Cache::forget($lang . '_primary_menus');
            }
        }
    }
}
