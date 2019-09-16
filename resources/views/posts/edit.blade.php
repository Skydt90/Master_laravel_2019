@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>
    <form method="POST" action="{{ route('post.update', ['post' => $post->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('posts.partials._form')
        <input type="submit" value="Update" class="btn btn-primary btn-block">
    </form>

@endsection