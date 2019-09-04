<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
    <ul>
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('contact') }}">Contact</a></li>
    <li><a href="{{ route('post.index') }}">Blog Posts</a></li>
    <li><a href="{{ route('post.create') }}">Create Post</a></li>
    </ul>
    @if (session()->has('success'))

        <p style="color:green">
            {{ session()->get('success') }}
        </p>
        
    @endif
    @yield('content')
</body>