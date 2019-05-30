@inject('Organisation', 'App\Helpers\OrganisationHelper')
@extends('layouts.Backend')

{{-- Document title not page title --}}
@title('My Dashboard')

{{-- Page title --}}
@page_title('My Dashboard')

{{-- Document/Body title --}}
@body_class('client-profiles layout-full')

@section('content')   
  <style>
  .menu-box {
    background: #fff;
    margin: 15px 0;
  }
  .disabled .menu-box{
    background: #fafafa;
  }
  .menu-box .counter {
    font-size: 48px;
    margin-right: 20px;
    margin-top: 20px;
  }
  .small-box.disabled h1 {
    color: #aaa!important;
  }
  .bem-page__heading-text span {
    font-size: 14px;
  }
  .small-box.disabled img {
    -webkit-filter: grayscale(1);
            filter: grayscale(1);
  }
  </style>        
  <div class="container-fluid"> 
    <div class="row">          
        <a href="{{ route('client.profiles') }}" class="small-box">
          <div class="col-lg-4 col-md-6">                       
              <div class="menu-box r-pad-3x">                
                  <div class="img-container">
                    <h1 class="n-space"><strong>Clients</strong></h1>
                    <img src="/images/insurance-interface/clients.gif" class="v-space-2x">
                    <h1 class="counter pull-right"><strong>{{ $Organisation->countAllClients() }}</strong></h1>   
                  </div>
                     
              </div>
          </div>
        </a> 
        <a href="{{ route('rfqs.index') }}" class="small-box">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-3x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>RFQs</strong></h1>
                  <img src="/images/insurance-interface/policies.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $Organisation->countAllRFQs() }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>
        <!-- <a href="{{ route('insurancequotes.index') }}" class="small-box"> -->
        <a href="#" class="small-box disabled">
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-3x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Quotes</strong></h1>
                  <img src="/images/insurance-interface/quotes.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $Organisation->countAllQuotes() }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>  
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-3x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Policies</strong></h1>
                  <img src="/images/insurance-interface/policies.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $Organisation->countAllPolicies() }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a> 
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-3x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Claims</strong></h1>
                  <img src="/images/insurance-interface/claims.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $Organisation->countAllClaims() }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a> 
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-3x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Action Items</strong></h1>
                  <img src="/images/insurance-interface/action-items.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $Organisation->countAllTasks() }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>
    </div>
  </div>                
@endsection

