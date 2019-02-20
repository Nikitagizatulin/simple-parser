@extends('layout.master')

@section('title', 'Add page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-white">Parsing video</h1>
            <form action="{{url('/parser')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="search">Link of video</label>
                    <textarea class="form-control" id="search" name="search" rows="5" placeholder="https://www.youtube.com/watch?v=kXYiU_JCYtU"></textarea>
                </div>
                <input type="submit" class="btn btn-primary" value="Search">
            </form>
        </div>
    </div>
@endsection

