<?php
namespace App\Providers;

use App\Models\Topic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (request()->routeIs('admin.*')) {
                return;
            }

            $menus = Cache::remember(app()->getLocale() . '_primary_menus', now()->addHours(6), function () {
                return Topic::select('id', 'slug')
                    ->withWhereHas('translations')
                    ->where('type', 'menu')
                    ->where('is_primary', 1)
                    ->active()
                    ->orderBy('position')
                    ->get();
            });

            $view->with('menus', $menus);
        });
    }
}
