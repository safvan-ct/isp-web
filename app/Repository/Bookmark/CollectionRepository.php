<?php
namespace App\Repository\Bookmark;

use App\Models\BookmarkCollection;

class CollectionRepository implements CollectionInterface
{
    public function getWithBookmarkCount(int $userId, ?bool $paginate = true)
    {
        $query = BookmarkCollection::withCount('items')
            ->where('user_id', $userId)
            ->orderByDesc('items_count');

        return $paginate ? $query->paginate(9) : $query->get();
    }

    public function firstOrCreate(array $data)
    {
        return BookmarkCollection::firstOrCreate(
            ['slug' => $data['slug'], 'user_id' => $data['user_id']],
            ['name' => $data['name']]
        );
    }

    public function update(array $data, BookmarkCollection $collection): void
    {
        $collection->update($data);
    }

    public function destroy(array $where): void
    {
        BookmarkCollection::where($where)->delete();
    }

    public function getCollectionWithBookmarks(int $userId, ?int $collectionId = null)
    {
        $query = BookmarkCollection::select('id', 'name')
            ->with('items:id,bookmark_collection_id,bookmarkable_id,bookmarkable_type')
            ->where('user_id', $userId);

        if ($collectionId) {
            return $query->where('id', $collectionId)->first();
        }

        return $query->orderBy('name', 'asc')->get();
    }
}
