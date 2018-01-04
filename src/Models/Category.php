<?php

namespace Thahulive\Easeblog\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'meta_description'
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
     * @return BelongsToMany
     */
     public function posts()
     {
         return $this->belongsToMany(Post::class)
             ->orderBy('created_at', 'DESC');
     }

     /**
      * Get the post count relationship.
      *
      * @return BelongsToMany
      */
      public function postsCount($status = 'published')
      {
          return $this->belongsToMany(Post::class)
              ->where('status',$status)->count();
      }

}
