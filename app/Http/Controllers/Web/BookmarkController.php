<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repository\Bookmark\BookmarkInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function __construct(protected BookmarkInterface $bookmarkRepository)
    {}

    public function store(Request $request)
    {
        $request->validate([
            'BookmarkType' => 'required|string|in:' . implode(',', array_keys(config('constants.type_map'))),
            'BookmarkItem' => 'required|integer',
            'CollectionId' => 'required|exists:bookmark_collections,id',
        ]);

        $data = [
            'bookmarkable_id'        => $request->BookmarkItem,
            'bookmarkable_type'      => config('constants.type_map.' . $request->BookmarkType),
            'bookmark_collection_id' => $request->CollectionId,
            'user_id'                => Auth::id(),
        ];

        $exists = $this->bookmarkRepository->checkUserBookmarkExist($data);

        $message        = $exists ? 'removed' : 'added';
        $found          = true;
        $collectionItem = null;

        if ($exists) {
            $this->bookmarkRepository->destroy($data);

            unset($data['bookmark_collection_id']);
            $found = $this->bookmarkRepository->checkUserBookmarkExist($data);
        } else {
            $collectionItem = $this->bookmarkRepository->create($data);
        }

        return response()->json(['status' => $message, 'collectionItem' => $collectionItem, 'found' => $found]);
    }
}
