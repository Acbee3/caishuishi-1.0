@extends('agent.authorizations.app_authorizations')

@section('css')
    @parent
    <link rel="stylesheet" href="{{url("agent/common/css/agent/zTreeStyle.css")}}" type="text/css">
    <link rel="stylesheet" href="{{url("agent/common/css/agent/officer.css")}}">
@endsection

@section('content')
    <div class="container_agerant" style="margin-bottom: 0;">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/authorizations")}}" class="cur">客户授权</a>
        </div>
    </div>
    <iframe src="{{url("/agent/authorizations/lists")}}" frameborder="0" width="100%" height="100%" style="min-height: 800px;" id="authIframe"></iframe>
@endsection

@section('footer')
    @include('agent.common.footer')
@endsection