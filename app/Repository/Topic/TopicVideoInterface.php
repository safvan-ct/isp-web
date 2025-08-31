<?php
namespace App\Repository\Topic;

use App\Models\TopicVideo;
use Illuminate\Http\Request;

interface TopicVideoInterface
{
    public function get(int $id);

    public function dataTable(Request $request);

    public function create(array $data);

    public function update(array $data, TopicVideo $topicVideo);

    public function sort(array $data);
}
