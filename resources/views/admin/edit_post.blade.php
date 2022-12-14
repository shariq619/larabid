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
                            <h1 class="page-header"> {{ $title }}
                                <a href="{{ route('create_new_post') }}" class="btn btn-info pull-right"> <i class="fa fa-floppy-o"></i> @lang('app.create_new_post')</a>

                            </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">

                        <form action="" id="createPostForm" class="form-horizontal" method="post" enctype="multipart/form-data"> @csrf

                        <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" value="{{ old('title')?old('title'): $post->title }}" name="title" placeholder="@lang('app.title')">
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('post_content')? 'has-error':'' }}">
                            <div class="col-sm-12">
                                <textarea name="post_content" id="post_content" class="form-control">{!!  old('post_content')? old('post_content'): $post->post_content !!}</textarea>
                                {!! $errors->has('post_content')? '<p class="help-block">'.$errors->first('post_content').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('images')? 'has-error':'' }}">
                            <div class="col-sm-12">

                                <div id="uploaded-ads-image-wrap">
                                    @if($post->feature_img)

                                        @php $img = $post->feature_img @endphp
                                        <div class="creating-ads-img-wrap">
                                            <img src="{{ media_url($img, false) }}" class="img-responsive" />
                                            <div class="img-action-wrap" id="{{ $img->id }}">
                                                <a href="javascript:;" class="imgDeleteBtn"><i class="fa fa-trash-o"></i> </a>
                                                <a href="javascript:;" class="imgFeatureBtn"><i class="fa fa-star{{ $img->is_feature ==1 ? '':'-o' }}"></i> </a>
                                            </div>
                                        </div>

                                    @endif
                                </div>


                                <div class="file-upload-wrap">
                                    <label>
                                        <input type="file" name="images" id="images" style="display: none;" />
                                        <i class="fa fa-cloud-upload"></i>
                                        <p>@lang('app.upload_image')</p>
                                    </label>
                                </div>

                                <div class="clearfix"></div>
                                <p class="text-info">@lang('app.post_img_resize_info')</p>
                                {!! $errors->has('images')? '<p class="help-block">'.$errors->first('images').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">@lang('app.update_post')</button>
                                <input type="hidden" name="post_id" value="{{ $post->id }}" />
                            </div>
                        </div>
                        </form>

                    </div>

                </div>


            </div>   <!-- /#page-wrapper -->




        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'post_content' );
    </script>
    <script>
        $(document).ready(function() {
            $("#images").change(function () {
                var fd = new FormData(document.querySelector("form#createPostForm"));
                $.ajax({
                    url: '{{ route('upload_post_image') }}',
                    type: "POST",
                    data: fd,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    success: function (data) {
                        $('#loadingOverlay').hide('slow');
                        if (data.success == 1) {
                            $('#uploaded-ads-image-wrap').load('{{ route('append_post_media_image') }}');
                        } else {
                            toastr.error(data.msg, '<?php echo trans('app.error') ?>', toastr_options);
                        }
                    }
                });
            });

            $('body').on('click', '.imgDeleteBtn', function () {
                //Get confirm from user
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }

                var current_selector = $(this);
                var img_id = $(this).closest('.img-action-wrap').attr('id');
                $.ajax({
                    url: '{{ route('delete_media') }}',
                    type: "POST",
                    data: {media_id: img_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            current_selector.closest('.creating-ads-img-wrap').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });
    </script>
@endsection