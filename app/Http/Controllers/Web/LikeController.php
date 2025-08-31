<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repository\Like\LikeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct(protected LikeInterface $likeRepository)
    {}

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(config('constants.type_map'))),
            'id'   => 'required|integer',
        ]);

        $exists = $this->likeRepository->checkUserLikeExist(Auth::id(), $request->id, $request->type);
        $data   = [
            'user_id'       => Auth::id(),
            'likeable_id'   => $request->id,
            'likeable_type' => config('constants.type_map.' . $request->type),
        ];

        if ($exists) {
            $this->likeRepository->destroy($data);
            return response()->json(['status' => 'removed']);
        } else {
            $this->likeRepository->create($data);
            return response()->json(['status' => 'added']);
        }
    }

    public function sync(Request $request)
    {
        $user    = Auth::user();
        $likes   = $request->likes ?? [];
        $typeMap = config('constants.type_map');

        // Sync likes
        foreach ($likes as $like) {
            $id   = (int) $like['id'];
            $type = $typeMap[$like['type']] ?? null;

            if (! isset($type) || ! $id) {
                continue;
            }

            $this->likeRepository->create(['user_id' => $user->id, 'likeable_id' => $id, 'likeable_type' => $type]);
        }

        return response()->json(['status' => 'success']);
    }
}
