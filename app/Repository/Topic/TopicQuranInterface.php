<?php
namespace App\Repository\Topic;

use App\Models\TopicQuranVerse;
use Illuminate\Http\Request;

interface TopicQuranInterface
{
    public function get(int $id);

    public function dataTable(Request $request);

    public function create(array $data);

    public function update(array $data, TopicQuranVerse $topicQuranVerse);

    public function sort(array $data);
}
