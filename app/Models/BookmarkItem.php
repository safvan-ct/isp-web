<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookmarkItem extends Model
{
    protected $fillable = ['user_id', 'bookmark_collection_id', 'bookmarkable_id', 'bookmarkable_type'];

    public function bookmarkable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collection()
    {
        return $this->belongsTo(BookmarkCollection::class);
    }
}
