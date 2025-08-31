<?php
namespace App\Repository\Topic;

use App\Models\Topic;

interface TopicInterface
{
    public function dataTable(string $type, $parentId = null);

    public function create(array $data, string $type);

    public function toggleActive(Topic $topic);

    public function sort(array $data);

    public function get(?int $id = null, ?string $type = null);

    public function getMenuWithAll($slug);

    public function getModuleWithAll($slug);

    public function getModulesHasMenu();

    public function getQuestionWithAll($slug);

    public function getTopicById(array $id, $paginate = false);

    public function getLikedTopics($userId, $paginate = true);

    public function getBookmarkedTopics($userId, $collectionId, $paginate = true);
}
