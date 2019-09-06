@extends('layouts.app')

@section('content')
<h2>Blog Posts</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
        <th>Show</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    @forelse ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->content }}</td>
                <td><a href="{{ route('post.show', ['post' => $post->id]) }}" class="btn btn-primary">show</a></td>
                <td><a href="{{ route('post.edit', ['post' => $post->id]) }}" class="btn btn-primary">edit</a></td>
                <td><form method="POST" class="fm-inline" action="{{ route('post.destroy', ['post' => $post->id]) }}">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="delete">
                </form></td>
                @empty
                <p>No posts yet!</p>    
            </tr>
        </table>
    @endforelse
@endsection