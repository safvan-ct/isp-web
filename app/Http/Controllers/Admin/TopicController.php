<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\StoreRequest;
use App\Http\Requests\Topic\UpdateRequest;
use App\Models\Topic;
use App\Repository\Topic\TopicInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class TopicController extends Controller implements HasMiddleware
{
    public function __construct(protected TopicInterface $topicRepository)
    {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'view menus', 'view modules', 'view questions', 'view answers',
            ]), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using([
                'store menus', 'store modules', 'store questions', 'store answers',
            ]), only: ['store']),
            new Middleware(PermissionMiddleware::using([
                'update menus', 'update modules', 'update questions', 'update answers',
            ]), only: ['update']),
            new Middleware(PermissionMiddleware::using([
                'sort menus', 'sort modules', 'sort questions', 'sort answers',
            ]), only: ['sort']),
            new Middleware(PermissionMiddleware::using([
                'active menus', 'active modules', 'active questions', 'active answers',
            ]), only: ['status']),
        ];
    }

    public function index($type)
    {
        $parentType = config("constants.topic_parent_map.$type");
        if ($parentType === null && $type !== 'menu') {
            abort(404);
        }

        $parents = $this->topicRepository->get(null, $parentType);
        return view('admin.topic.index', compact('type', 'parents'));
    }

    public function store(StoreRequest $request, $type)
    {
        $this->topicRepository->create($request->validated(), $type);
        return response()->json(['message' => 'Menu created successfully']);
    }

    public function update(UpdateRequest $request, $type, Topic $topic)
    {
        $topic->update(['slug' => Str::slug($request->slug), 'is_primary' => $request->is_primary]);
        return response()->json(['message' => ucwords($type) . ' updated successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->topicRepository->dataTable($request->type, $request->parent_id))->make(true);
    }

    public function status(Topic $topic)
    {
        $this->topicRepository->toggleActive($topic);
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function sort(Request $request)
    {
        $this->topicRepository->sort($request->order);
        return response()->json(['message' => 'Items sorted successfully']);
    }
}
