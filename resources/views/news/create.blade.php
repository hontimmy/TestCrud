@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                        <div class="card-header"><strong>Add a new News</strong></div>

                        <div class="card-body">
                            <form method="POST" action="/news/create">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter a title">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="content" placeholder="Cnntent" rows="8">
                                    </textarea>
                                </div>
                                <button class="btn btn-primary" type="Submit" >Add News</button>
                            </form>
                        </div>
                </div>

                @if(count($errors))
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>
                                {{$error}}
                            </li>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
