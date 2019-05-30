@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Sign in')

{{-- Page title --}}
@page_title('CMI Data - Sign in')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('body')
        <div class="bem-members__logo-container">
            <a href="/" title="CMI Data">
                <img src="/images/logo-cmi.png">
            </a>
        </div>
        <div class="row form-center">
            <div class="col-md-12">
                <div class="bem-members__container-main">
                    @include('flash::message')
                    <div class="bem-form__container-sm bem-page__container-white bem-form__container-rounded bem-container__center">
                        <h5 class="bem-text_center">Sign in to your session</h5>
                        <form class="form-horizontal" role="form">
                            {{ csrf_field() }}  
                            <div class="bem-social__links-auth bem-text_center">                                         
                                <a href="{{ route('googlelogin') }}" class="btn btn-block bem-social__btn btn-google btn-flat"><i class="fa fa-google-plus"></i>Sign in with
            Google+</a>         
                                 <div class="bem-divider__with-or-block">
                                    <span class="bem-divider__with-or-text">or</span>
                                </div>                          
                                <a href="{{ route('facebooklogin') }}" class="btn btn-block bem-social__btn btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in with
                Facebook</a>                                              
                            </div>                                
                        </form>                               
                    </div>  
                     <p class="bem-text_right bem-member__links-sub-menu">
                        <a href="{{ route("quotes.request") }}" class="">Obtain a Quote</a> | 
                        <a href="{{ route("admin-login") }}" class="">Advisor Sign in</a> | 
                        <a href="{{ route("register-front") }}" class="">Register</a> | 
                        <a href="{{ route("inquiries.create") }}" class="">Submit an Inquiry</a>
                    </p>                     
                </div>               
            </div>    
        </div>
@endsection

