@extends('layout.master')

@section('title', 'Edit page')

@section('content')
    <h1 class="text-white">Edit page</h1>
    @if(empty($video))
        <h2 class="text-white">There is no such video</h2>
    @else
        <form action="{{url('/parser/' . $video->id)}}" enctype="multipart/form-data" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="header">Header video:</label>
                        <input type="text" name="header" class="form-control" id="header" value="{{$video->header}}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control" rows="10">{{ $video->description }}</textarea>
                    </div></div>
                <div class="col-md-6 pt-4">
                    <div class="form-group">
                        <img src="{{asset('images/' . $video->img)}}" alt="oldImg" class="img-thumbnail center-block">
                        <br>
                        <label for="img">Link image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="img" name="img">
                            <label class="custom-file-label" for="img">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" value="Update" class="btn btn-primary btn-lg">
        </form>
    @endif
@endsection