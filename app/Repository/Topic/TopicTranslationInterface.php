<?php
namespace App\Repository\Topic;

use App\Models\TopicTranslation;

interface TopicTranslationInterface
{
    public function dataTable($topicId = null);

    public function create(array $data);

    public function update(array $data, TopicTranslation $topicTranslation);

    public function toggleActive(TopicTranslation $topicTranslation);
}
