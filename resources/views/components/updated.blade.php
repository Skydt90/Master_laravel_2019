<p class="text-muted">
    {{ empty(trim($slot)) ? 'Added ' : $slot}} {{ $date->diffForHumans() }}
    @if (isset($name))
        @if(isset($userId))
        by <a href="{{ route('user.show', ['user' => $userId]) }}">{{$name}}</a>
        @endif
        @else
        by {{$name}}
    @endif
</p>