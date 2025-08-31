<?php
namespace App\Repository\Topic;

use App\Models\TopicTranslation;

class TopicTranslationRepository implements TopicTranslationInterface
{
    public function dataTable($topicId = null)
    {
        return TopicTranslation::where('topic_id', $topicId);
    }

    public function create(array $data): TopicTranslation
    {
        return TopicTranslation::create($data);
    }

    public function update(array $data, TopicTranslation $topicTranslation): void
    {
        $topicTranslation->update($data);
    }

    public function toggleActive(TopicTranslation $topicTranslation): void
    {
        $topicTranslation->update(['is_active' => ! $topicTranslation->is_active]);
    }
}
