<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicVideo extends Model
{
    protected $fillable = ['topic_id', 'video_id', 'title', 'position'];
}
