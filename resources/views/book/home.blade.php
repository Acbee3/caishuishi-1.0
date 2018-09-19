@extends('book.layout.main')
@section('title')
    {{ $data->company_name }} _ 企业管理
@endsection
@section('table')
   <div class="container">
       <img style="padding: 10px 10px; width:80%" src="{{url("agent/common/images/home_process.jpg")}}" title="{{ $data->company_name }} - 记账流程"/>
   </div>
@endsection