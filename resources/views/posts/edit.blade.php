@extends('layouts.app')

@section('content')
    <h1>Create new post</h1>
    <form method="POST" action="{{ route('post.update', ['post' => $post->id]) }}">
        @csrf
        @method('PUT')
        @include('posts.partials._form')
        <input type="submit" value="Update" class="btn btn-primary btn-block">
    </form>

@endsection