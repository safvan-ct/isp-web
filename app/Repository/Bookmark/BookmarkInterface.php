<?php
namespace App\Repository\Bookmark;

interface BookmarkInterface
{
    public function checkUserBookmarkExist(array $where);

    public function create(array $data);

    public function destroy(array $where);

    public function insertOrIgnore(array $data);
}
