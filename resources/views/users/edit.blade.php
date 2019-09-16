@extends('layouts.app')

@section('content')
    <form action="POST" action="{{ route('user.update', ['user' => $user->id])}}" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-4">
                <img src="" alt="img-thumbnail avatar">
                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload different photo</h6>
                        <input type="file" name="avatar" class="form-control-file">
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" value="" name="name">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                </div>

            </div>
        </div>
    </form>
    
@endsection