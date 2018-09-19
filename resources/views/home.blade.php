@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="display: none;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container" >
        <div class="col-md-12" style="background: #fff;">
            123
        </div>

    </div>
</div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection
