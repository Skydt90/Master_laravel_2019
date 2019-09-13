<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Comment;
use App\Http\Requests\StorePost;
use App\User;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    

    public function __construct()
    {
        //$this->middleware('auth', ['only' => 'index']);
        $this->middleware('auth');
    }

    public function index()
    {
        //return view('posts.index', ['posts' => BlogPost::all()]);

        //store most commented in cache for 10 seconds.
        $mostCommented = Cache::remember('most-commented-blog-posts', now()->addSeconds(10), function () {
            return BlogPost::mostCommented()->take(3)->get();
        });

        //store 30 mins
        $mostActive = Cache::remember('users-most-active', 30, function () {
            return User::withMostBlogPosts()->take(3)->get();
        });

        $mostActviveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function () {
            return User::withMostBlogPostsLastMonth()->take(3)->get();
        });

        //comment count for each bp
        return view('posts.index', 
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(), 
                'mostCommented' => $mostCommented,
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActviveLastMonth
            ]);
        
        //->with('posts', BlogPost::latest()->withCount('comments')->get(), 'mostCommented', BlogPost::mostCommented()->take(5)->get());
    
    }


    public function show(BlogPost $post)
    {
       /*//1: When passing id
        //fetch blogpost with related comments (closure modifies query)
        return view('posts.show')
            ->with('post', BlogPost::with(['comments' => function($query) {
                return $query->latest();
            }])
            ->findOrFail($post->id)); */
        
        /* //2: Object model binding - loading only the comments, since model is already present    
        $post->comments = Comment::latest()
            ->where('blog_post_id', $post->id)
            ->get();
        */ 

        
        //caching
        $blogPost = Cache::remember("blog-post-{$post->id}", 30, function () use ($post) {
            return BlogPost::with('comments')->findOrFail($post->id);
        });

        $counter = 0;
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$post->id}-counter";
        $usersKey = "blog-post-{$post->id}-users";

        $users = Cache::get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= 1) {
            $difference++;
        }

        $usersUpdate[$sessionId] = $now;

        Cache::forever($usersKey, $usersUpdate);

        if(!Cache::has($counterKey)) {
            Cache::forever($counterKey, 1);
        } else {
            Cache::increment($counterKey, $difference);
        }

        $counter = Cache::get($counterKey);

        return view('posts.show', [
                'post' => $blogPost,
                'counter' => $counter,
            ]);  
    }


    public function create()
    {
        return view('posts.create');
    }


    public function store(StorePost $request)
    {
        $data = $request->validated();

        //adding user id to data array using user method on request, 
        //which fetches current authenticated user
        $data['user_id'] = $request->user()->id;
 
        if($post = BlogPost::create($data)) {
            session()->flash('success', 'Post was created!');
        }
        return redirect()->route('post.show', ['post' => $post->id]);
    }


    public function edit(BlogPost $post)
    {
        //use gate closure to deny editing of posts not created by user
        /* if(Gate::denies('update-post', $post)) {
            abort(403, "You can't edit this blog post!");
        } */

        $this->authorize('update', $post);
        
        return view('posts.edit')->with('post', $post);
    }
        
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $request->validated();
        $post->fill($data);

        if($post->save()) {
            session()->flash('success', 'Post was updated successfully!');
        }
        return redirect()->route('post.show', ['post' => $post->id]);
    }

    
    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);

        if(BlogPost::destroy($post->id)) {
            session()->flash('success', 'Post was deleted!');
        }
        return redirect()->route('post.index');

    }
}
