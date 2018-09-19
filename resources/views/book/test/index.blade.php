{{-- 公共资源文件，包含在 /resource/views/book/layout/base.blade.php --}}
@extends('book.layout.base')

{{--css内容区--}}
@section('css')
    @parent
    <style>
        .class_1 {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="">
@endsection

{{-- html内容区 --}}
@section('content')

@endsection

{{--js内容区--}}
@section('script')
    @parent
    <script src=""></script>
    <script>
        // ...
    </script>
@endsection