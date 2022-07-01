@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h2>News Detail</h2>
                </div>
                <div class="card">
                    <div class="card-header">{{$news->title}}</div>

                    <div class="card-body">
                        {{$news->content}}
                    </div>
                    <div class="card-footer">
                        <a href="/news/{{$news->id}}/edit" class="btn btn-warning">Edit News</a>

                        <form style="float:right" method="POST" action="/news/{{$news->id}}">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
     
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
