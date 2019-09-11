@extends('layouts.app')

@section('content')
    
    <h1>Contact</h1>
    <p>Contact page content</p>
    
    @can('home.secret')
    <p>
        <a href="{{ route('secret') }}">Special contact page</a>
    </p>
    @endcan
@endsection

</html>
