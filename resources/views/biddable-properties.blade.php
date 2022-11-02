@extends('layouts.app')
@section('title') Biddable Properties  @endsection

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Biddable Properties </h2>
                </div>
            </div>
        </div>
    </div>



    <div class="single-categories-more">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form class="show-property-detail-form" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Select the property for bidding/submit offer:</label>
                            <select class="form-control" name="property" id="property">
                                <option value="0">--Select--</option>
                                @foreach($properties as $property)
                                    @if($property->is_bid_active())
                                        <option value="{{$property->id}}">{{$property->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group form-check">
                            <label>Select User Type:</label>
                            <input type="radio" class="form-check-input user_type" value="buyer" name="user_type">I am a
                            Buyer
                            <input type="radio" class="form-check-input user_type" value="broker" name="user_type">I am
                            a Broker
                        </div>

                        <div class="mt-10 buyer_container" style="margin-top:50px;display: none">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact" class="form-control" required>
                            </div>

                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary mb-100">Next</button>
                            <br>
                            <br>
                        </div>
                    </form>

                    <div class="mt-10 broker_container" style="margin-top:50px;display: none">
                        <div id="result"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Register</h3>

                                <form class="register-form" id="register-form" method="post">
                                    @csrf
                                    <input type="hidden" name="property_id" id="property_id" value="">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="contact" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Create Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <br>
                                    <br>
                                    <button type="submit" class="btn btn-primary mb-100">Register</button>
                                    <br>
                                    <br>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h3>Login</h3>
                                <form class="login-form" method="post" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                               required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <br>
                                    <br>
                                    <button type="submit" class="btn btn-primary mb-100">Login</button>
                                    <br>
                                    <br>
                                </form>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <br><br>  <br><br>  <br><br>

    <script src="{{asset('js/custom.js')}}"></script>

@endsection
