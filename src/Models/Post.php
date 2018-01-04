<?php

namespace Thahulive\Easeblog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Post extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at'];

    protected $appends = array('formatedViewCount');
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'subtitle',
        'meta_description',
        'content',
        'image',
        'status',
        'published_at'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return an approximate reading time for the post. Based on reading time of 275 WPM.
     *
     * @return int
     */
    public function readingTime()
    {
        $readingTime = round(str_word_count($this->content) / 275);
        if($readingTime < 1){
          return 'Less than 1 Min reading';
        }
        return $readingTime . 'Min Reading.';
    }

    /**
     * Return post created time
     *
     * @return int
     */
    public function createdTime()
    {
        $dt = Carbon::now();
        if($dt->diffInWeeks($this->created_at) > 3){
          return $this->created_at->format('H:i a d M Y');
        }
        return $this->created_at->diffForHumans();
    }

    public function getFormatedViewCountAttribute()
    {
        $number = $this->views_count;
        $precision = 1;
        $divisors = null;

        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );
        }

        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                break;
            }
        }

        $count = number_format($number / $divisor, $precision);
        return floatval($count) . $shorthand;
    }

    /**
     * Get the posts relationship.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tags relationship.
     *
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the categories relationship.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
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

    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', 'published')
            ->where('published_at','<=',Carbon::now());
    }

    public function scopeDrafted(Builder $query)
    {
        return $query->where('status', '=', 'drafted');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy('created_at','DESC');
    }
}
