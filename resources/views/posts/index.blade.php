@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        <h1>Blog Posts</h1>
        @forelse ($posts as $post)
            <p>
                <h3>
                    @if($post->trashed())
                        <del>
                    @endif
                        <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
                            href="{{ route('post.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                    @if($post->trashed())
                        </del>  
                    @endif
                </h3>

                @updated(['date' => $post->created_at, 'name' => $post->user->name, 'userId' => $post->user->id])
                @endupdated
                @tags(['tags' => $post->tags])@endtags

                @if($post->comments_count)
                    <p>{{ $post->comments_count }} comments</p>
                @else
                    <p>No comments yet!</p>
                @endif

                @auth
                    @can('update', $post) 
                        <a href="{{ route('post.edit', ['post' => $post->id]) }}"
                            class="btn btn-primary">
                            Edit
                        </a>
                    @endcan
                @endauth
                
                @auth
                    @if (!$post->trashed())
                        @can('delete', $post)
                            <form method="POST" class="fm-inline"
                                action="{{ route('post.destroy', ['post' => $post->id]) }}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete!" class="btn btn-danger"/>
                            </form>
                        @endcan
                    @endif 
                @endauth
            </p>
        @empty
            <p>No posts yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials._activity')
    </div> 
</div>       
@endsection

