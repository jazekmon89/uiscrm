@extends('layouts.switcher')

@section('css')

@endsection

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        @include('flash::message')
        <div class="panel panel-default">
            <div class="panel-heading">New User - Cheking for existing account</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="GET" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="first_name" class="text-center col-md-12">{{ trans('messages.temp_pass_msg_header',['media'=>'email']) }}</label>
                        <!--br /-->
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="text-center col-md-12">{{ trans('messages.temp_pass_msg_body',['duration'=>'10']) }}</label>
                    </div>

                    <div class="form-group">
                        <div class="text-center col-md-12">
                            <button type="submit" class="btn btn-primary">
                                Continue to Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
