@extends('layouts.app')
@section('title') Documents Approving  @endsection

@section('content')

    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Documents Approving </h2>
                </div>
            </div>
        </div>
    </div>



    <div class="single-categories-more">
        <div class="container">
            <div class="row">
                <div class="col-md-8">


                    @if(isset($message))
                        <div class="alert alert-success">{{$message}}</div>
                    @endif


                </div>
            </div>
        </div>
    </div>
   

@endsection
