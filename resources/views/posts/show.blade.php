@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        <h2>
            <strong>Title:</strong> {{ $post->title }}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 3])
                New post!
            @endbadge
        </h2>
        <p><strong>Content:</strong> {{ $post->content }}</p>
        @updated(['date' => $post->created_at, 'name' => $post->user->name])
        @endupdated
        @updated(['date' => $post->updated_at])
            Updated 
        @endupdated
        @tags(['tags' => $post->tags])@endtags
        
        <p>Currently read by: {{ $counter }} people</p>

        <h4>Comments</h4>
        @forelse ($post->comments as $comment)
            <p>{{ $comment->content }}</p>
            @updated(['date' => $comment->created_at])
            @endupdated
        @empty
            <p>No Comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials._activity')
    </div> 
@endsection