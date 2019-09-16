<div class="mb-2 mt-2">
    
    @auth
        <form method="POST" action="{{ route('post.comments.store', ['post' => $post->id]) }}">
            @csrf
    
            <div class="form-group">
                <textarea type="text" name="content" class="form-control"></textarea>
            </div>
    
            <input type="submit" value="Add Comment" class="btn btn-primary btn-block">
        </form>
        @errors 
        @enderrors
    @else
        <a href="{{ route('login')}}">Login</a> to post a comment!    
    @endauth

</div>
<hr/>