<?php
namespace App\Repository\Like;

use App\Models\Like;

class LikeRepository implements LikeInterface
{
    public function checkUserLikeExist($userId, $likeableId, $likeableType)
    {
        return Like::where('user_id', $userId)
            ->where('likeable_type', config('constants.type_map.' . $likeableType))
            ->where('likeable_id', $likeableId)
            ->exists();
    }

    public function create(array $data): Like
    {
        return Like::firstOrCreate($data);
    }

    public function destroy(array $where): void
    {
        Like::where($where)->delete();
    }
}
