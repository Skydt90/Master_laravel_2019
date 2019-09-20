@extends('layouts.app')

@section('content')

    <h1>{{ __('messages.welcome') }}</h1>
    
    <p>{{ __('messages.example_with_value', ['name' => 'Christian']) }}</p>

    <p>{{ trans_choice('messages.plural', 0) }}</p>
    <p>{{ trans_choice('messages.plural', 1) }}</p>
    <p>{{ trans_choice('messages.plural', 2) }}</p>

    <p>Using JSON: {{ __('Welcome') }}</p>
    <p>Using JSON: {{ __('Hello :name', ['name' => 'Christian']) }}</p>

    <p>This is the content of main page</p>

</html>  
@endsection
