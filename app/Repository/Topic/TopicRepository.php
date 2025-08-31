<?php
namespace App\Repository\Topic;

use App\Models\BookmarkItem;
use App\Models\Like;
use App\Models\Topic;
use App\Models\TopicTranslation;
use Illuminate\Support\Str;

class TopicRepository implements TopicInterface
{
    public function dataTable(string $type, $parentId = null)
    {
        return Topic::select('id', 'slug', 'is_active', 'position', 'is_primary')
            ->where('type', $type)
            ->when($parentId, fn($q) => $q->where('parent_id', $parentId));
    }

    public function create(array $data, string $type)
    {
        $position = Topic::where('type', $type)->count() + 1;

        $query = Topic::create([
            'slug'       => Str::slug($data['slug']),
            'type'       => $type,
            'position'   => $position,
            'parent_id'  => $data['parent_id'] ?? null,
            'is_primary' => $data['is_primary'] ?? 0,
        ]);

        if (in_array($type, ['menu', 'module', 'question'])) {
            TopicTranslation::create([
                'topic_id' => $query->id,
                'lang'     => 'en',
                'title'    => ucwords($data['slug']),
            ]);
        }

        return $query;
    }

    public function toggleActive(Topic $topic): void
    {
        $topic->update(['is_active' => ! $topic->is_active]);
    }

    public function sort(array $data): void
    {
        foreach ($data as $item) {
            Topic::where('id', $item['id'])->update(['position' => $item['position']]);
        }
    }

    public function get(?int $id = null, ?string $type = null)
    {
        $query = Topic::with('translations')
            ->when($type, fn($q) => $q->where('type', $type));

        return $id ? $query->find($id) : $query->get();
    }

    public function getMenuWithAll($slug)
    {
        return Topic::select('id', 'slug')
            ->withWhereHas('translations')
            ->withWhereHas('children.translations')
            ->where('type', 'menu')
            ->where('slug', $slug)
            ->first();
    }

    public function getModuleWithAll($slug)
    {
        return Topic::select('id', 'slug', 'parent_id', 'position')
            ->withWhereHas('translations')
            ->withWhereHas('parent.translations')
            ->withWhereHas('children.translations')
            ->where('type', 'module')
            ->where('slug', $slug)
            ->first();
    }

    public function getModulesHasMenu()
    {
        return Topic::select('id', 'slug', 'parent_id')
            ->withWhereHas('translations')
            ->withWhereHas('parent.translations')
            ->where('type', 'module')
            ->where('is_primary', 1)
            ->get();
    }

    public function getQuestionWithAll($slug)
    {
        return Topic::select('id', 'slug', 'parent_id', 'position')
            ->withWhereHas('translations')
            ->withWhereHas('parent.translations')
            ->withWhereHas('children.translations')
            ->with([
                'children.quranVerses',
                'children.hadithVerses',
                'children.videos',
            ])
            ->where('type', 'question')
            ->where('slug', $slug)
            ->active()
            ->first();
    }

    public function getTopicById(array $id, $paginate = false)
    {
        $query = Topic::select('id', 'slug', 'parent_id')
            ->withWhereHas('translations')
            ->with([
                'parent.translations',
                'parent.parent.translations',
                'parent.parent.parent.translations',
            ])
            ->where('type', 'answer')
            ->whereIn('id', $id);

        return $paginate ? $query->paginate(5) : $query->get();
    }

    public function getLikedTopics($userId, $paginate = true)
    {
        $ids = Like::where('likeable_type', Topic::class)
            ->where('user_id', $userId)
            ->pluck('likeable_id')
            ->toArray();

        return $this->getTopicById($ids, $paginate);
    }

    public function getBookmarkedTopics($userId, $collectionId, $paginate = true)
    {
        $ids = BookmarkItem::where('bookmarkable_type', Topic::class)
            ->where('bookmark_collection_id', $collectionId)
            ->where('user_id', $userId)
            ->pluck('bookmarkable_id')
            ->toArray();

        return $this->getTopicById($ids, $paginate);
    }
}
