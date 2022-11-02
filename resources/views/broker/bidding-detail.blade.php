@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <style>
        .required_field {
            color: red
        }
    </style>
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
                    <div class="col-xs-12"><br>
                        <div class="page-header">
                            <div class="container">

                                <div class="row">
                                    <div class="col-md-12">
                                        <h2> Documents Detail </h2>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form class="bidding-detail" method="post" action=""
                              enctype="multipart/form-data">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(isset($detail->approved))
                                @if($detail->approved == 1)
                                    <div class="alert alert-success">Documents are approved</div>
                                    <a href="{{route('single_ad',[ $ad->id, $ad->slug])}}">
                                        <button class="btn btn-success" type="button">Go to Auction</button>
                                    </a>

                                @endif
                            @endif

                            <br><br>

                            @if(isset($ad) && (!$ad->is_bid_active()))
                                <div class="alert alert-danger">Auction ended for the property you have registered.
                                    Please register to participate another auction
                                </div>
                            @endif


                            @if(Session::has('message'))
                                <div class="alert alert-success">
                                    {{\Illuminate\Support\Facades\Session::get('message')}}
                                </div>
                            @endif

                            <div class="form-group {{ $errors->has('bidding_propery_address')? 'has-error':'' }}">

                                <div class="col-sm-8">
                                    <label>Bidding Property Address <span class="required_field">*</span></label>
                                    <textarea class="form-control"
                                              name="bidding_property_address">
                                        {{ old('bidding_property_address', $detail->bidding_property_address ?? '')}}</textarea>
                                    {!! $errors->has('bidding_property_address')? '<p class="help-block">'.$errors->first('bidding_property_address').'</p>':'' !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('name')? 'has-error':'' }}">

                                <div class="col-sm-8">
                                    <label>Name <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                           value="{{ old('name', $detail->name ?? '')}}">
                                    {!! $errors->has('name')? '<p class="help-block">'.$errors->first('name').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('cell_num')? 'has-error':'' }}">

                                <div class="col-sm-8">
                                    <label>Cell Number <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="cell_num"
                                           value="{{ old('cell_num', $detail->cell_num ?? '')}}">
                                    {!! $errors->has('cell_num')? '<p class="help-block">'.$errors->first('cell_num').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('brokerage_name')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Brokerage Name <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="brokerage_name"
                                           value="{{ old('brokerage_name', $detail->brokerage_name ?? '')}}">
                                    {!! $errors->has('brokerage_name')? '<p class="help-block">'.$errors->first('brokerage_name').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('brokerage_address')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Brokerage Address <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="brokerage_address"
                                           value="{{ old('brokerage_address', $detail->brokerage_address ?? '')}}">
                                    {!! $errors->has('brokerage_address')? '<p class="help-block">'.$errors->first('brokerage_address').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('buyer_1_name')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Buyer 1 Name <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="buyer_1_name"
                                           value="{{ old('buyer_1_name', $detail->buyer_1_name ?? '')}}">
                                    {!! $errors->has('buyer_1_name')? '<p class="help-block">'.$errors->first('buyer_1_name').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('buyer_2_name')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Buyer 2 Name <span class="required_field">*</span></label>
                                    <input type="text" class="form-control" name="buyer_2_name"
                                           value="{{ old('buyer_2_name', $detail->buyer_2_name ?? '')}}">
                                    {!! $errors->has('buyer_2_name')? '<p class="help-block">'.$errors->first('buyer_2_name').'</p>':'' !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h1>Upload Documents</h1>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('offer_to_purchase')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Offer To Purchase <span class="required_field">*</span></label>
                                    <input type="file" class="form-control" name="offer_to_purchase">
                                    {!! $errors->has('offer_to_purchase')? '<p class="help-block">'.$errors->first('offer_to_purchase').'</p>':'' !!}

                                    @if(isset($detail->offer_to_purchase))
                                        <a href="{{asset($detail->offer_to_purchase)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->offer_to_purchase)}}" target="_blank">Preview</a>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('proof_of_fund')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Pre Approval Letter/ Proof Of Funds <span
                                                class="required_field">*</span></label>
                                    <input type="file" class="form-control" name="proof_of_fund">
                                    {!! $errors->has('proof_of_fund')? '<p class="help-block">'.$errors->first('proof_of_fund').'</p>':'' !!}
                                    @if(isset($detail->proof_of_fund))
                                        <a href="{{asset($detail->proof_of_fund)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->proof_of_fund)}}" target="_blank">Preview</a>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('mls_copy')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>MLS Copy</label>
                                    <input type="file" class="form-control" name="mls_copy">
                                    {!! $errors->has('mls_copy')? '<p class="help-block">'.$errors->first('mls_copy').'</p>':'' !!}

                                    @if(isset($detail->mls_copy))
                                        <a href="{{asset($detail->mls_copy)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->mls_copy)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('seller_disclosure')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Sellers Disclosure</label>
                                    <input type="file" class="form-control" name="seller_disclosure">
                                    {!! $errors->has('seller_disclosure')? '<p class="help-block">'.$errors->first('seller_disclosure').'</p>':'' !!}

                                    @if(isset($detail->seller_disclosure))
                                        <a href="{{asset($detail->seller_disclosure)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->seller_disclosure)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('other_document_1')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Other Documents</label>
                                    <input type="file" class="form-control" name="other_document_1">
                                    {!! $errors->has('other_document_1')? '<p class="help-block">'.$errors->first('other_document_1').'</p>':'' !!}

                                    @if(isset($detail->other_document_1))
                                        <a href="{{asset($detail->other_document_1)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->other_document_1)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('other_document_2')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Other Documents</label>
                                    <input type="file" class="form-control" name="other_document_2">
                                    {!! $errors->has('other_document_2')? '<p class="help-block">'.$errors->first('other_document_2').'</p>':'' !!}

                                    @if(isset($detail->other_document_2))
                                        <a href="{{asset($detail->other_document_2)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->other_document_2)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('other_document_3')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Other Documents</label>
                                    <input type="file" class="form-control" name="other_document_3">
                                    {!! $errors->has('other_document_3')? '<p class="help-block">'.$errors->first('other_document_3').'</p>':'' !!}

                                    @if(isset($detail->other_document_3))
                                        <a href="{{asset($detail->other_document_3)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->other_document_3)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('other_document_4')? 'has-error':'' }}">
                                <div class="col-sm-8">
                                    <label>Other Documents</label>
                                    <input type="file" class="form-control" name="other_document_4">
                                    {!! $errors->has('other_document_4')? '<p class="help-block">'.$errors->first('other_document_4').'</p>':'' !!}

                                    @if(isset($detail->other_document_4))
                                        <a href="{{asset($detail->other_document_4)}}" download>Download</a>
                                        |
                                        <a href="{{asset($detail->other_document_4)}}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>
                            @if(!isset($detail->approved) || $detail->approved!=1)


                                <div class="form-group">
                                    <div class="col-sm-8"><br>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </form>

                    </div>

                </div>
                <br> <br>
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