<?php
namespace App\Repository\Bookmark;

use App\Models\BookmarkItem;

class BookmarkRepository implements BookmarkInterface
{
    public function checkUserBookmarkExist(array $where)
    {
        return BookmarkItem::where($where)->exists();
    }

    public function create(array $data): BookmarkItem
    {
        return BookmarkItem::create($data);
    }

    public function destroy(array $where): void
    {
        BookmarkItem::where($where)->delete();
    }

    public function insertOrIgnore(array $data)
    {
        return BookmarkItem::insertOrIgnore($data);
    }
}
