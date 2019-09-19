@component('mail::message')
# Commet was posted was posted on post you're watching

Hi {{ $user->name }}!

@component('mail::button', ['url' => route('post.show', ['post' => $comment->commentable->id])])
View the blog post
@endcomponent

@component('mail::button', ['url' => route('user.show', ['user' => $comment->user->id])])
View {{ $comment->user->name }}'s profile
@endcomponent

@component('mail::panel')
{{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
