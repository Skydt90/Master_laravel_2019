<p class="text-muted">
    {{ empty(trim($slot)) ? 'Added ' : $slot}} {{ $date->diffForHumans() }}
    @if (isset($name) && !isset($userId))
        by {{$name}}
    @endif
    @if(isset($userId))
        by <a href="{{ route('user.show', ['user' => $userId]) }}">{{$name}}</a>
    @endif
</p>