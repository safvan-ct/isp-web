<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BookmarkCollection;
use App\Repository\Bookmark\BookmarkInterface;
use App\Repository\Bookmark\CollectionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookmarkCollectionController extends Controller
{
    public function __construct(
        protected CollectionInterface $collectionRepository,
        protected BookmarkInterface $bookmarkRepository
    ) {}

    public function index()
    {
        $collections = $this->collectionRepository->getWithBookmarkCount(Auth::id());
        return view('web.collections', compact('collections'));
    }

    public function show(BookmarkCollection $collection)
    {
        return view('web.collection', compact('collection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'BookmarkType' => 'required|string|in:' . implode(',', array_keys(config('constants.type_map'))),
            'BookmarkItem' => 'required|integer',
            'name'         => 'required|string',
        ]);

        $data = [
            'bookmarkable_id'        => $request->BookmarkItem,
            'bookmarkable_type'      => config('constants.type_map.' . $request->BookmarkType),
            'bookmark_collection_id' => null,
            'user_id'                => Auth::id(),
        ];

        try {
            // Find or create collection
            $collection = $this->collectionRepository->firstOrCreate([
                'slug'    => Str::slug($request->name),
                'user_id' => $data['user_id'],
                'name'    => $request->name,
            ]);

            $newCollection                  = $collection->wasRecentlyCreated;
            $data['bookmark_collection_id'] = $collection->id;

            $exists = $this->bookmarkRepository->checkUserBookmarkExist($data);
            if (! $exists) {
                $this->bookmarkRepository->create($data);
            }

            // Load updated collection with items
            $result = $this->collectionRepository->getCollectionWithBookmarks($data['user_id'], $data['bookmark_collection_id']);

            return response()->json(['status' => 'added', 'collection' => $result, 'newCollection' => $newCollection]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, BookmarkCollection $collection)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $this->collectionRepository->update(['name' => $request->name, 'slug' => Str::slug($request->name)], $collection);
        return redirect()->route('collections.index')->with('success', 'Collection name updated successfully.');
    }

    public function destroy($id)
    {
        $this->collectionRepository->destroy(['id' => $id, 'user_id' => Auth::id()]);
        return redirect()->route('collections.index')->with('success', 'Collection deleted successfully.');
    }

    public function fetchCollections()
    {
        $result = $this->collectionRepository->getCollectionWithBookmarks(Auth::id());
        return response()->json($result);
    }
}
