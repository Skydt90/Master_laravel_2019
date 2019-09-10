<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\StorePost;

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

        // comments count for each bp
        return view('posts.index')->with('posts', BlogPost::withCount('comments')->get());
    }


    public function show($id)
    {
        return view('posts.show')->with('post', BlogPost::with('comments')->findOrFail($id));
         // ['post' => BlogPost::findOrFail($id)]);
    }


    public function create()
    {
        return view('posts.create');
    }


    public function store(StorePost $request)
    {
        $data = $request->validated();
 
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
