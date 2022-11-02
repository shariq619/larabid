@extends('layouts.app')
@section('title') Biddable Properties  @endsection

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="container">
        <br>
        <div class="alert alert-success">Property detail has been sent to you via email</div>
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2> {{$property->title}} </h2>
                    </div>
                </div>
            </div>
        </div>


        <div class="single-categories-more">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">

                        <p>{!! $property->description !!}</p>

                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Category</th>
                                <td>{{$property->sub_category->category_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{$property->price?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Seller Name</th>
                                <td>{{$property->seller_name?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Seller Email</th>
                                <td>{{$property->seller_email?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Seller Phone</th>
                                <td>{{$property->seller_phone?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td> {{$property->country->country_name ?? ''}}
                                    , {{$property->state->state_name ?? ''}}
                                    , {{$property->city->city_name ?? ''}} <br>
                                    {{$property->address}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td> {{$property->category_type}}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/custom.js')}}"></script>

@endsection
