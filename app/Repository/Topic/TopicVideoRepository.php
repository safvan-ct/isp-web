<?php
namespace App\Repository\Topic;

use App\Models\TopicVideo;
use Illuminate\Http\Request;

class TopicVideoRepository implements TopicVideoInterface
{
    public function get(int $id): TopicVideo
    {
        return $id == 0 ? new TopicVideo() : TopicVideo::findOrFail($id);
    }

    public function dataTable(Request $request)
    {
        $query = TopicVideo::where('topic_id', $request->topic_id);

        if (! $request->order) {
            return $query->orderBy('position');
        }

        return $query;
    }

    public function create(array $data): TopicVideo
    {
        $position         = TopicVideo::count() + 1;
        $data['position'] = $position;
        return TopicVideo::create($data);
    }

    public function update(array $data, TopicVideo $topicVideo): void
    {
        $topicVideo->update($data);
    }

    public function sort(array $data): void
    {
        foreach ($data as $item) {
            TopicVideo::where('id', $item['id'])->update(['position' => $item['position']]);
        }
    }
}
