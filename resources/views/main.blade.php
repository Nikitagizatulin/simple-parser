@extends('layout.master')

@section('title', 'Main page')

@section('content')
    <h1 class="text-white">History</h1>
    @if(empty($allVideo->total()))
        <h2 class="text-white">There are no parsing videos yet</h2>
    @else
        <div class="row">
            @foreach($allVideo as $video)
                <div class="col-md-12 mb-3">
                    <div class="media border p-3 bg-white">
                        <div class="media-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{asset('images/' . $video->img)}}"
                                         alt="{{$video->header}}"
                                         class="mr-3 mt-3 img-thumbnail img-responsive"
                                         data-content="{{$video->iframe}}">
                                </div>
                                <div class="col-md-8">
                                    <div class="float-right d-flex">
                                        <a role="button" class="btn btn-warning mr-2 ml-2" href="{{ url('/parser/' . $video->id .'/edit') }}">Edit</a>
                                        <form action="{{ url('/parser/' . $video->id) }}" method="POST" class="delete">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" role="button" class="btn btn-danger" value="Delete">
                                        </form>
                                    </div>
                                    <h3 class="font-weight-bold">{{$video->header}}</h3>
                                    <div class="content">
                                        {!! $video->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $allVideo->links() }}
        </div>
    @endif
@endsection