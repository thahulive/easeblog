<?php

namespace Thahulive\Easeblog\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'ip'
    ];
    /**
     * Get the user relationship.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post relationship.
     *
     * @return BelongsTo
     */
     public function posts()
     {
         return $this->belongsTo(Post::class);
     }

}
