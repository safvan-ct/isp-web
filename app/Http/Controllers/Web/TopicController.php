<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repository\Topic\TopicInterface;

class TopicController extends Controller
{
    protected $webVersion;
    protected $questions;

    public function __construct(protected TopicInterface $topicRepository)
    {
        $this->webVersion = config("constants.web_version");
    }

    public function modules($menuSlug)
    {
        $topic = $this->topicRepository->getMenuWithAll($menuSlug);
        if (! $topic) {
            abort(404);
        }

        return view("web.modules", compact("topic", "menuSlug"));
    }

    public function questions($menuSlug, $moduleSlug)
    {
        $module = $this->topicRepository->getModuleWithAll($moduleSlug);
        if (! $module) {
            abort(404);
        }

        return view("web.questions", compact("module", "menuSlug"));
    }

    public function answers($menuSlug, $moduleSlug, $questionSlug)
    {
        $question = $this->topicRepository->getQuestionWithAll($questionSlug);
        $module   = $this->topicRepository->getModuleWithAll($moduleSlug);
        if (! $question) {
            abort(404);
        }

        return view("web.answers", compact("question", "module", "menuSlug", "moduleSlug"));
    }
}
