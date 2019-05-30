@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('UIS CRM - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}
@js('js/uis.js', 'uisjs', 'app')
@js('js/app.min.js', 'distappjs', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')

@section('body')
        <div class="members-logo">
            <a href="/">
                <img src="/images/logo-cmi.png">
            </a>
        </div>
        <div class="row form-center">
            <div class="col-md-12">
                <div class="login-box">
                    @include('flash::message')

                    <div class="members-box-body">
                        <h5 class="text-center">Register using the following</h5>
                        <form class="form-horizontal" role="form">
                            {{ csrf_field() }}  
                            <div class="social-auth-links text-center">                                         
                                <a href="{{ route('googlereg') }}" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i>Register with
            Google+</a>         
                                <div class="divider-or">
                                  <span class="text-or">or</span>
                                </div>                          
                                <a href="{{ route('facebookreg') }}" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i>Register with
                Facebook</a>                                              
                            </div>                                
                        </form>                               
                    </div>  
                     <p class="text-right members-text-links">
                        <a href="{{ route("quotes.request") }}" class="">Obtain a Quote</a> |
                        <a href="{{ route("login") }}" class="">Sign In</a> | 
                        <a href="{{ route("inquiries.create") }}" class="">Submit an Inquiry</a>
                    </p>                     
                </div>               
            </div>    
        </div>
@endsection
