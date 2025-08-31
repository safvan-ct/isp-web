<?php
namespace App\Repository\Bookmark;

use App\Models\BookmarkCollection;

interface CollectionInterface
{
    public function getWithBookmarkCount(int $userId, ?bool $paginate = true);

    public function firstOrCreate(array $data);

    public function update(array $data, BookmarkCollection $collection);

    public function destroy(array $where);

    public function getCollectionWithBookmarks(int $userId, ?int $collectionId = null);
}
