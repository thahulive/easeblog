<?php

namespace Thahulive\Easeblog\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Thahulive\Easeblog\Models\Category;
use Thahulive\Easeblog\Models\Post;
use Thahulive\Easeblog\Models\Tag;
use Thahulive\Easeblog\Models\View;
use Thahulive\Easeblog\Models\User;

class EaseBlogController extends Controller
{
    public function showPosts(Request $request)
    {
      $posts = $this->latestPosts();

      $categories = $this->categoryPosts();

      $tags = $this->tagPosts();

      $page = collect();
      $page->title = 'Aboo\'s Latest Posts';

      return View('vendor.easeblog.sample',compact('posts','categories','tags','page'));
    }

    public function show(Request $request, $slug)
    {
        $post = Post::whereSlug($slug)->published()->firstOrFail();

        $this->createPostViews($request, $post);

        return view('vendor.easeblog.single',compact('post'));
    }

    public function userPosts($userId, $status = 'all', $setter = 'get')
    {
        $this->posts = Post::whereUserId($userId);

        if($status == 'published'){
          $this->posts = $this->posts->published();
        }

        if($status == 'drafted'){
          $this->posts = $this->posts->drafted();
        }

        if($status == 'all'){
          return $this->posts = $this->querySetter($this->posts, $setter);
        }

        if($setter == 'none'){
          return $this->posts;
        }

        return $this->querySetter($this->posts, $setter);
    }

    public function latestPosts()
    {
        $this->posts = Post::latest()->published();

        return $this->querySetter($this->posts);
    }

    public function categoryPosts($status = 'published', $setter = 'get')
    {
      $data = Category::whereHas('posts', function ($query) use ($status) {
        $query->where('status',$status);
      })->withCount('posts');

      return $this->querySetter($data, $setter);
    }

    public function userCategoryPosts($userId, $status = 'published', $setter = 'get')
    {
      $data = Category::whereHas('posts', function ($query) use ($userId,$status) {
        $query->where('user_id', $userId)->where('status',$status);
      })->withCount('posts');

      return $this->querySetter($data, $setter);
    }

    public function tagPosts($status = 'published', $setter = 'get')
    {
      $data = Tag::whereHas('posts', function ($query) use ($status) {
        $query->where('status',$status);
      })->withCount('posts');

      return $this->querySetter($data, $setter);
    }

    public function userTagPosts($userId, $status = 'published', $setter = 'get')
    {
      $data = Tag::whereHas('posts', function ($query) use ($userId,$status) {
        $query->where('user_id', $userId)->where('status',$status);
      })->withCount('posts');

      return $this->querySetter($data, $setter);
    }

    public function popularPosts($limit = 20)
    {
         $data = Post::published()->whereHas('views')
              ->orderBy('views_count','DESC');

          if($limit == 'paginate'){
            return $data->paginate();
          }

          return $data->limit($limit)->get();
    }

    public function createPostViews(Request $request, Post $post)
    {
        $ip = $request->ip();

        // for development purpose
        if( $ip == '::1'){
          $ip = "192.168.0.0";
        }

        $stat = $post->views()->updateOrCreate([
            'user_id' => (Auth::id()) ? Auth::id() : NULL,
            'ip' => $ip,
            'post_id' => $post->id
        ]);

        if($stat->wasRecentlyCreated){
          $post->increment('views_count');
        }
    }

    protected function querySetter($data, $setter = 'paginate')
    {
        if($setter == 'paginate'){
          return $data->paginate();
        }

        if($setter == 'get'){
          return $data->get();
        }

        return $data;
    }
}
