<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = ['role', 'first_name', 'last_name', 'email', 'password', 'phone', 'dob', 'sex', 'image', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['name'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function collections()
    {
        return $this->hasMany(BookmarkCollection::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(BookmarkItem::class);
    }
}
