@extends('layouts.app')

@section('content')
    <h1>Create new post</h1>
    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
        @csrf
        @include('posts.partials._form')
        <input type="submit" value="Submit" class="btn btn-primary btn-block">
    </form>

@endsection