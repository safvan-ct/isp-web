<?php
namespace App\Repository\Topic;

use App\Models\TopicHadithVerse;
use Illuminate\Http\Request;

interface TopicHadithInterface
{
    public function get(int $id);

    public function dataTable(Request $request);

    public function create(array $data);

    public function update(array $data, TopicHadithVerse $topicHadithVerse);

    public function sort(array $data);
}
