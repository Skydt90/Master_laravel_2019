@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        @if ($post->image)
        <div style="background-image: url('{{ $post->image->getUrl() }}'); min-height: 250px; color:white; text-align:center; background-attachment:fixed;">
            <h2 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
        <h2>
        @endif
            <strong>Title:</strong> {{ $post->title }}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 3])
                New post!
            @endbadge
        @if ($post->image)
            </h2>
        </div>
        @else
        </h2>
        @endif
        <p><strong>Content:</strong> {{ $post->content }}</p>

        {{-- <img src="{{ null !== $post->image ? $post->image->getUrl() : '' }}" alt="Image related to post"> --}}

        @updated(['date' => $post->created_at, 'name' => $post->user->name])
        @endupdated
        @updated(['date' => $post->updated_at])
            Updated 
        @endupdated
        @tags(['tags' => $post->tags])@endtags
        
        <p>Currently read by: {{ $counter }} people</p>

        <h4>Comments</h4>
        @include('comments.partials._form')
        @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            @updated(['date' => $comment->created_at, 'name' => $comment->user->name])
            @endupdated
        @empty
            <p>No Comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials._activity')
    </div> 
@endsection