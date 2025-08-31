<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\HadithStoreRequest;
use App\Http\Requests\Topic\HadithUpdateRequest;
use App\Models\TopicHadithVerse;
use App\Repository\Topic\TopicHadithInterface;
use App\Repository\Topic\TopicInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class TopicHadithController extends Controller implements HasMiddleware
{
    public function __construct(
        protected TopicInterface $topicRepository,
        protected TopicHadithInterface $topicHadithRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(['view topic-hadith']), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using(['store topic-hadith']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['update topic-hadith']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['delete topic-hadith']), only: ['destroy']),
            new Middleware(PermissionMiddleware::using(['active topic-hadith']), only: ['status']),
        ];
    }
    public function index($topicId, $id = null)
    {
        $type   = 'answer';
        $topic  = $this->topicRepository->get($topicId, $type);
        $hadith = is_null($id) ? null : $this->topicHadithRepository->get($id);

        return view('admin.topic.hadith', compact('type', 'topic', 'hadith'));
    }

    public function store(HadithStoreRequest $request)
    {
        try {
            $this->topicHadithRepository->create($request->only(['topic_id', 'hadith_verse_id', 'simplified', 'translation_json']));
            return redirect()->route('admin.topic-hadith.index', [$request->topic_id])
                ->with('success', 'Topic Hadith created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-hadith.index', [$request->topic_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(HadithUpdateRequest $request, TopicHadithVerse $topicHadith)
    {
        try {
            $this->topicHadithRepository->update($request->only(['simplified', 'translation_json']), $topicHadith);
            return redirect()->route('admin.topic-hadith.index', [$request->topic_id])
                ->with('success', 'Topic Hadith updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-hadith.index', [$request->topic_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(TopicHadithVerse $topicHadith)
    {
        $topicHadith->delete();
        return response()->json(['message' => 'Topic Hadith deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->topicHadithRepository->dataTable($request))->make(true);
    }

    public function sort(Request $request)
    {
        $this->topicHadithRepository->sort($request->order);
        return response()->json(['message' => 'Items sorted successfully']);
    }
}
