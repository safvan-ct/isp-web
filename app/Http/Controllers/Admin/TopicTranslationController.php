<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\TranslationStoreRequest;
use App\Models\TopicTranslation;
use App\Repository\Topic\TopicInterface;
use App\Repository\Topic\TopicTranslationInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class TopicTranslationController extends Controller implements HasMiddleware
{
    public function __construct(
        protected TopicInterface $topicRepository,
        protected TopicTranslationInterface $topicTranslationRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'view menu-translations', 'view module-translations', 'view question-translations', 'view answer-translations',
            ]), only: ['inde-translationx', 'dataTable']),
            new Middleware(PermissionMiddleware::using([
                'store menu-translations', 'store module-translations', 'store question-translations', 'store answer-translations',
            ]), only: ['store']),
            new Middleware(PermissionMiddleware::using([
                'update menu-translations', 'update module-translations', 'update question-translations', 'update answer-translations',
            ]), only: ['update']),
            new Middleware(PermissionMiddleware::using([
                'active menu-translations', 'active module-translations', 'active question-translations', 'active answer-translations',
            ]), only: ['status']),
        ];
    }

    public function index($type, $id, $translationId = null)
    {
        $topic       = $this->topicRepository->get($id);
        $translation = $topic->translations()->find($translationId);

        return view('admin.topic.translation', compact('type', 'topic', 'translation'));
    }

    public function store(TranslationStoreRequest $request)
    {
        try {
            $this->topicTranslationRepository->create($request->validated());

            return redirect()->route('admin.topic-translations.index', [$request->type, $request->topic_id])
                ->with('success', 'Translation created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-translations.index', [$request->type, $request->topic_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function update(TranslationStoreRequest $request, TopicTranslation $topicTranslation)
    {
        try {
            $this->topicTranslationRepository->update($request->only(['lang', 'title', 'sub_title', 'content']), $topicTranslation);
            return redirect()->route('admin.topic-translations.index', [$request->type, $request->topic_id])
                ->with('success', 'Translation updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topic-translations.index', [$request->type, $request->topic_id])
                ->withInput($request->all())
                ->with('error', $e->getMessage());
        }
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->topicTranslationRepository->dataTable($request->topic_id))->make(true);
    }

    public function status(TopicTranslation $id)
    {
        $this->topicTranslationRepository->toggleActive($id);
        return response()->json(['message' => 'Status updated successfully']);
    }
}
