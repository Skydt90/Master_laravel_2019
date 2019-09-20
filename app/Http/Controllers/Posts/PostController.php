<?php

namespace App\Http\Controllers\Posts;

use App\BlogPost;
use App\Contracts\CounterContract;
use App\Events\BlogPostPosted;
use App\Http\Requests\StorePost;
use App\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    private $counterService;
    
    public function __construct(CounterContract $counterService)
    {
        //$this->middleware('auth', ['only' => 'index']);
        $this->counterService = $counterService;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('posts.index', [
                'posts' => BlogPost::latestWithRelations()->get()
            ]);
        //->with('posts', BlogPost::latest()->withCount('comments')->get(), 'mostCommented', BlogPost::mostCommented()->take(5)->get());
    }

    public function show(BlogPost $post)
    {
        //instantiating via service container resolve
        //$counterService = resolve(CounterService::class);
        
        //caching
        $blogPost = Cache::remember("blog-post-{$post->id}", 30, function () use ($post) {
            return BlogPost::with('comments', 'tags', 'user', 'comments.user') //fetching comments and the user relation of the comment itself
                ->findOrFail($post->id);
        });

        return view('posts.show', [
                'post' => $blogPost,
                'counter' => $this->counterService->getCurrentUserViewCount("blog-post-{$post->id}")
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
            session()->flash('status', 'Post was created!');
        }

        if($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(Image::make(['path' => $path]));
        };

        event(new BlogPostPosted($post));

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

        if($request->hasFile('thumbnail')) {
            
            $path = $request->file('thumbnail')->store('thumbnails');
            
            if($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            } else {
                $post->image()->save(Image::make(['path' => $path]));
            }
        };

        if($post->save()) {
            session()->flash('status', 'Post was updated successfully!');
        }
        return redirect()->route('post.show', ['post' => $post->id]);
    }


    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);

        if(BlogPost::destroy($post->id)) {
            session()->flash('status', 'Post was deleted!');
        }

        return redirect()->route('post.index');
    }
}
