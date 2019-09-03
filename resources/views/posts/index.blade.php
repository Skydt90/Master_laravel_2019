@extends('layouts.app')

@section('content')
<h2>Blog Posts</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
    </tr>
    @forelse ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->content }}</td>
                <td><a href="{{ route('post.show', ['post' => $post->id]) }}">show</a></td>
                @empty
                <p>No posts yet!</p>    
            </tr>
        </table>
    @endforelse
@endsection