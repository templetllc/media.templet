@extends('layouts.approval')
@section('title', 'Approval Image')
@section('content')
<div class="imgbob_dash">
    <div class="content" data-base="">
        @include('pages.gallery.approval')

        {{$images->appends(request()->query())->links() }}

    </div>
</div>
<script type="text/javascript">

    window.onload = function(){
        $('body').find('.media-item.loading').each(function(){
            $(this).removeClass('loading');
        });
    };
</script>
@endsection
