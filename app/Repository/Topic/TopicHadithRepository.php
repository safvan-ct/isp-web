<?php
namespace App\Repository\Topic;

use App\Models\TopicHadithVerse;
use Illuminate\Http\Request;

class TopicHadithRepository implements TopicHadithInterface
{
    public function get(int $id): TopicHadithVerse
    {
        return $id == 0 ? new TopicHadithVerse() : TopicHadithVerse::findOrFail($id);
    }

    public function dataTable(Request $request)
    {
        $query = TopicHadithVerse::with('hadith.chapter.book')->where('topic_id', $request->topic_id);

        if (! $request->order) {
            return $query->orderBy('position');
        }

        return $query;
    }

    public function create(array $data): TopicHadithVerse
    {
        $position         = TopicHadithVerse::count() + 1;
        $data['position'] = $position;
        return TopicHadithVerse::create($data);
    }

    public function update(array $data, TopicHadithVerse $topicHadithVerse): void
    {
        $topicHadithVerse->update($data);
    }

    public function sort(array $data): void
    {
        foreach ($data as $item) {
            TopicHadithVerse::where('id', $item['id'])->update(['position' => $item['position']]);
        }
    }
}
