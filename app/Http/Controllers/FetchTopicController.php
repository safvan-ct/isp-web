<?php
namespace App\Http\Controllers;

use App\Repository\Topic\TopicInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchTopicController extends Controller
{
    public function __construct(
        protected TopicInterface $topicRepository
    ) {}

    public function fetchLikedTopics(Request $request)
    {
        if (Auth::check() && Auth::user()->role == 'Customer') {
            $result = $this->topicRepository->getLikedTopics(Auth::id());
        } else {
            $ids    = array_values(array_filter($request->ids));
            $result = $this->topicRepository->getTopicById($ids, true);
        }

        return response()->json([
            'html'       => view('web.partials.topic-list', ['result' => $result, 'liked' => true])->render(),
            'pagination' => view('components.web.pagination', ['paginator' => $result])->render(),
        ]);
    }

    public function fetchBookmarkedTopics(Request $request)
    {
        $result = $this->topicRepository->getBookmarkedTopics(Auth::id(), $request->get('collection_id'));

        return response()->json([
            'html'       => view('web.partials.topic-list', ['result' => $result, 'bookmarked' => true])->render(),
            'pagination' => view('components.web.pagination', ['paginator' => $result])->render(),
        ]);
    }
}
