@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h2>All News</h2>
                </div>
                @foreach($news as $new)
                <div class="card">
                    <div class="card-header"><a href="/news/{{$new->id}}">{{$new->title}}</a></div>

                    <div class="card-body">
                        {{$new->content}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
