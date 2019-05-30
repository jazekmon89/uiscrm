@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Home')

{{-- Page title --}}
@page_title('CMI Data - Home')

{{-- Document/Body title --}}
@body_class('home')

@section('body')
    <div id="welcome" class="row">
        <div class="col-md-12">
             <div class="row">             
                <div id="logo" class="col-md-6">
                    <a href="/">
                        <img src="/images/logo-cmi.png" />
                    </a>
                    <h1 id="heading-welcome">Welcome to CMI Data</h1>
                </div>
                <div id="members-menu" class="col-md-6">  
                    <div class="btn-group-members">
                        <a href="{{ route("quotes.request") }}" class="btn btn-welcome btn-block icon-arrow-right" role="button">Obtain a Quote&nbsp;&nbsp;&nbsp;<i class="fa fa-file-text-o"></i></a> 
                        <a href="{{ route("login") }}" class="btn btn-welcome btn-block icon-arrow-right" role="button">Sign in&nbsp;&nbsp;&nbsp;<i class="fa fa-unlock"></i></a>                  
                        <a href="{{ route("register-front") }}" class="btn btn-welcome btn-block icon-arrow-right" role="button">Register&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus"></i></a>           
                        <a href="{{ route("inquiries.create") }}" class="btn btn-welcome btn-block icon-arrow-right" role="button">Submit an Inquiry&nbsp;&nbsp;&nbsp;<i class="fa fa-file-text"></i></a> 
                    </div>             
                </div>  
            </div>
        </div>
    </div>    
@endsection
