<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Comment;
use App\Http\Requests\StorePost;
use App\Services\CounterService;
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

        //comment count for each bp
        return view('posts.index', 
            [
                'posts' => BlogPost::latest()
                    ->withCount('comments')
                    ->with('user')
                    ->with('tags')
                    ->get(), 
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

        //instantiating via service container resolve
        $counterService = resolve(CounterService::class);
        
        //caching
        $blogPost = Cache::remember("blog-post-{$post->id}", 30, function () use ($post) {
            return BlogPost::with('comments')->with('tags')->with('user')->findOrFail($post->id);
        });

        return view('posts.show', [
                'post' => $blogPost,
                'counter' => $counterService->getCurrentUserViewCount("blog-post-{$post->id}")
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
