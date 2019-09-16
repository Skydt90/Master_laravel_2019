<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? null) }}">
</div>
<div class="form-group">
    <label for="content">Content</label>
    <input type="text" name="content" class="form-control" value="{{ old('content', $post->content ?? null) }}">
</div>
<div class="form-group">
    <label for="title">Thumbnail</label>
    <img src="{{ null !== $post->image ? $post->image->getUrl() : '' }}" alt="Image related to post" style="width:100px; height:100px;">
    <input type="file" name="thumbnail" class="form-control-file">
</div>
@errors 
@enderrors