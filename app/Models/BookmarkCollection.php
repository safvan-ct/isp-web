<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookmarkCollection extends Model
{
    protected $fillable = ['user_id', 'name', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(BookmarkItem::class);
    }
}
