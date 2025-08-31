<?php
namespace App\Repository\Topic;

use App\Models\TopicQuranVerse;
use Illuminate\Http\Request;

class TopicQuranRepository implements TopicQuranInterface
{
    public function get(int $id): TopicQuranVerse
    {
        return $id == 0 ? new TopicQuranVerse() : TopicQuranVerse::findOrFail($id);
    }

    public function dataTable(Request $request)
    {
        $query = TopicQuranVerse::with('quran')->where('topic_id', $request->topic_id);

        if (! $request->order) {
            return $query->orderBy('position');
        }

        return $query;
    }

    public function create(array $data): TopicQuranVerse
    {
        $position         = TopicQuranVerse::count() + 1;
        $data['position'] = $position;
        return TopicQuranVerse::create($data);
    }

    public function update(array $data, TopicQuranVerse $topicQuranVerse): void
    {
        $topicQuranVerse->update($data);
    }

    public function sort(array $data): void
    {
        foreach ($data as $item) {
            TopicQuranVerse::where('id', $item['id'])->update(['position' => $item['position']]);
        }
    }
}
