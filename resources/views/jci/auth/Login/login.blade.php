@extends('jci.layouts.Frontend')

{{-- Document title not page title --}}
@title('Sign In')

{{-- Page title --}}
@page_title('JCI - Sign In')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('content')
<div class="bem-form__container-sm bem-page__container-white bem-form__container-rounded bem-container__center">
    @include('flash::message')
    <h5 class="bem-text_center">Sign in to your session</h5>
    <form class="form-horizontal" role="form">
        {{ csrf_field() }}  
        <div class="bem-social__links-auth bem-text_center">                                         
            <a href="{{ route('googlelogin') }}" class="btn btn-block bem-social__btn btn-google"><i class="fa fa-google-plus"></i>Sign in with
Google+</a>         
            <div class="bem-divider__with-or-block">
                <span class="bem-divider__with-or-text">or</span>
            </div>                          
            <a href="{{ route('facebooklogin') }}" class="btn btn-block bem-social__btn btn-facebook"><i class="fa fa-facebook"></i> Sign in with
Facebook</a>                                              
        </div>                                
    </form>                                         
</div>    
@endsection
<style type="text/css">
    .bem-page__heading-text {
        display: none;
    }    
    .bem-footer {
        margin-top: 0 !important;
        position: fixed !important;
        bottom: 0;
    }  
    @media (max-width: 768px) {
        .bem-member-page__heading-container {
            margin: 75px 0 15px !important;
        }
    }  
</style>


