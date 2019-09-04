@extends('layouts.app')

@section('content')
    <h1>Create new post</h1>
    <form method="POST" action="{{ route('post.store') }}">
        @csrf
        <p>
            <label for="title">Title</label>
            <input type="text" name="title">
        </p>
        <p>
            <label for="content">Content</label>
            <input type="text" name="content">
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>

@endsection