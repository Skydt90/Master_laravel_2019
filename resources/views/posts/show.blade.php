@extends('layouts.app')

@section('content')
    <h1>Post Info</h1>
    <p><strong>Title:</strong> {{ $post->title }}</p>
    <p><strong>Content:</strong> {{ $post->content }}</p>
    <p><strong>Created at:</strong> {{ $post->created_at }}</p>
    <p><strong>Time since creation:</strong> {{ $post->created_at->diffForHumans() }}</p>
    <p><strong>Updated at:</strong> {{ $post->updated_at }}</p>
    
    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5 )
        <strong>New Post!</strong>
    @endif
    
    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        <p class="text-muted">Added: {{ $comment->created_at->diffForHumans() }}</p>
    @empty
        <p>No Comments yet!</p>
    @endforelse
@endsection