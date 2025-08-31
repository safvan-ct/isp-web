<?php
namespace App\Repository\Like;

interface LikeInterface
{
    public function checkUserLikeExist($userId, $likeableId, $likeableType);

    public function create(array $data);

    public function destroy(array $where);
}
