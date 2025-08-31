<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\VideoStoreRequest;
use App\Models\TopicVideo;
use App\Repository\Topic\TopicInterface;
use App\Repository\Topic\TopicVideoInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class TopicVideoController extends Controller implements HasMiddleware
{
    public function __construct(
        protected TopicInterface $topicRepository,
        protected TopicVideoInterface $topicVideoRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['view topic-video']), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using(['store topic-video']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['update topic-video']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['delete topic-video']), only: ['destroy']),
            new Middleware(PermissionMiddleware::using(['active topic-video']), only: ['status']),
        ];
    }
    public function index($topicId, $id = null)
    {
        $type  = 'answer';
        $topic = $this->topicRepository->get($topicId, $type);
        $video = is_null($id) ? null : $this->topicVideoRepository->get($id);

        return view('admin.topic.video', compact('type', 'topic', 'video'));
    }

    public function store(VideoStoreRequest $request)
    {
        try {
            $this->topicVideoRepository->create($request->only(['topic_id', 'video_id', 'title']));
            return redirect()->route('admin.topic-video.index', [$request->topic_id])
                ->with('success', 'Topic Video created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-video.index', [$request->topic_id, 0])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(VideoStoreRequest $request, TopicVideo $topicVideo)
    {
        try {
            $this->topicVideoRepository->update($request->only(['video_id', 'title']), $topicVideo);
            return redirect()->route('admin.topic-video.index', [$request->topic_id])
                ->with('success', 'Topic Video updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-video.index', [$request->topic_id, $topicVideo->id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(TopicVideo $topicVideo)
    {
        $topicVideo->delete();
        return response()->json(['message' => 'Topic Video deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->topicVideoRepository->dataTable($request))->make(true);
    }

    public function sort(Request $request)
    {
        $this->topicVideoRepository->sort($request->order);
        return response()->json(['message' => 'Items sorted successfully']);
    }
}
