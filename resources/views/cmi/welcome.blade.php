@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Home')

{{-- Page title --}}
@page_title('CMI Data - Home')

{{-- Document/Body title --}}
@body_class('home')

@section('body')
    <div class="bem-welcome__container">
        <div class="col-md-12">
             <div class="row">             
                <div class="bem-welcome__logo-container col-md-6">
                    <a href="/">
                        <img src="/images/logo-cmi.png" />
                    </a>
                    <h1 class="bem-welcome__heading">Welcome to CMI Data</h1>
                </div>
                <div class="bem-welcome__members-menu-container col-md-6">  
                    <div class="bem-btn-group_members-area">
                        <!--a disabled=true href="#{{-- route("quotes.request") --}}" class="btn bem-btn_welcome btn-block icon-arrow-right" role="button">Obtain a Quote&nbsp;&nbsp;&nbsp;<i class="fa fa-file-text-o"></i></a-->
                        <a disabled=true href="#{{-- route("login") --}}" class="btn bem-btn_welcome btn-block icon-arrow-right" role="button">Client Sign in&nbsp;&nbsp;&nbsp;<i class="fa fa-unlock"></i></a> 
                        <a href="{{ route("admin-login") }}" class="btn bem-btn_welcome btn-block icon-arrow-right" role="button">Advisor Sign in&nbsp;&nbsp;&nbsp;<i class="fa fa-sign-in"></i></a>                 
                        <a disabled=true href="#{{-- route("register-front") --}}" class="btn bem-btn_welcome btn-block icon-arrow-right" role="button">Register&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus"></i></a>           
                        <!--a disabled=true href="#{{-- route("inquiries.create") --}}" class="btn bem-btn_welcome btn-block icon-arrow-right" role="button">Submit an Inquiry&nbsp;&nbsp;&nbsp;<i class="fa fa-file-text"></i></a-->
                    </div>             
                </div>  
            </div>
        </div>
    </div>    
@endsection
