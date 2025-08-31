<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repository\Topic\TopicInterface;

class HomeController extends Controller
{
    protected $webVersion;

    public function __construct(protected TopicInterface $topicRepository)
    {
        $this->webVersion = config('constants.web_version');
    }

    public function changeLanguage($lang)
    {
        if (in_array($lang, array_keys(config('app.languages')))) {
            session()->put('lang', $lang);
            app()->setLocale(session('lang', 'en'));
        }

        return redirect()->back();
    }

    public function index()
    {
        $modules = $this->topicRepository->getModulesHasMenu();
        return view("web.index", compact("modules"));
    }

    public function calendar()
    {
        return view("web.calendar");
    }

    public function likes()
    {
        return view("web.likes");
    }
}
