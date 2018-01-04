<?php

namespace Thahulive\Easeblog\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'about',
        'avatar',
        'email',
        'password',
    ];
    /**
     * Get the posts relationship.
     *
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the tags relationship.
     *
     * @return HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Get the categories relationship.
     *
     * @return HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the views relationship.
     *
     * @return HasMany
     */
    public function views()
    {
        return $this->hasMany(View::class);
    }

    /**
     * Get the number of posts by a user.
     *
     * @param $userId
     *
     * @return bool
     */
    public static function postCount($userId)
    {
        return Post::where('user_id', $userId)->get()->count();
    }
}
