@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div class="container">

        <div id="wrapper">
            @include('admin.sidebar_menu')
            <div id="page-wrapper">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')


                <div class="row">
                    <div class="col-xs-12">
                        <br>
                        @if(isset($message))
                            <div class="alert alert-success">
                                {{$message}}
                            </div>
                        @endif
                        @if(isset($property_detail))

                            <div class="page-header">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2> {{$property_detail->title}} </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-categories-more">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-8">

                                            <p>{!! $property_detail->description !!}</p>

                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Category</th>
                                                    <td>{{$property_detail->sub_category->category_name ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Price</th>
                                                    <td>{{$property_detail->price?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Seller Name</th>
                                                    <td>{{$property_detail->seller_name?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Seller Email</th>
                                                    <td>{{$property_detail->seller_email?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Seller Phone</th>
                                                    <td>{{$property_detail->seller_phone?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td> {{$property_detail->country->country_name ?? ''}}
                                                        , {{$property_detail->state->state_name ?? ''}}
                                                        , {{$property_detail->city->city_name ?? ''}} <br>
                                                        {{$property_detail->address}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td> {{$property_detail->category_type}}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>






                        @endif
                    </div>
                </div>
            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection

@section('page-js')

    <script>
        @if(session('success'))
        toastr.success('{{ session('success') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
        @if(session('error'))
        toastr.error('{{ session('error') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
    </script>

@endsection