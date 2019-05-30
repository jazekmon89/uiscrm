@inject('Organisation', 'App\Helpers\OrganisationHelper')
@extends('layouts.Backend')

{{-- Document title not page title --}}
@title('General Insurance Dashboard')

@php 
  
  $organisations = ["all" => "All"] + arr_pairs($Organisations, "OrganisationID", "Name");
  $attributes = ['class' => 'form-control', 'onchange' => 'changeOrganisation(this)'];
  $dropdown = Form::jInput('select', "OrganisationID", $organisations, $OrganisationID, $attributes);

@endphp

{{-- Page title --}}
@page_title('General Insurance Dashboard <span class="grid-30 disp-inline-block">'. $dropdown .'</span>')

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
  <script>
    changeOrganisation = function(select) 
    {
      location.href = '{{ route('general-insurance', 'ORGANISATIONID') }}'.replace('ORGANISATIONID', select.value || 'all');
    }
  </script>     
  <div class="container-fluid"> 
    <div class="row">        
        <a href="{{ route('client.profiles') }}" class="small-box">
          <div class="col-lg-4 col-md-6">                       
              <div class="menu-box r-pad-5x">                
                  <div class="img-container">
                    <h1 class="n-space"><strong>Clients</strong></h1>
                    <img src="/images/insurance-interface/clients.gif" class="v-space-2x">
                    <h1 class="counter pull-right"><strong>{{ $counter->Client }}</strong></h1>   
                  </div>
                     
              </div>
          </div>
        </a> 
        <a href="{{ route('rfqs.index') }}" class="small-box">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-5x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>RFQs</strong></h1>
                  <img src="/images/insurance-interface/policies.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $counter->RFQ }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-5x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Quotes</strong></h1>
                  <img src="/images/insurance-interface/quotes.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $counter->Quote }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>  
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-5x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Policies</strong></h1>
                  <img src="/images/insurance-interface/policies.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $counter->Policy }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a> 
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-5x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Claims</strong></h1>
                  <img src="/images/insurance-interface/claims.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $counter->Claim }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a> 
        <a href="#" class="small-box disabled">  
          <div class="col-lg-4 col-md-6">
            <div class="menu-box r-pad-5x">
                <div class="img-container ">
                  <h1 class="n-space"><strong>Action Items</strong></h1>
                  <img src="/images/insurance-interface/action-items.gif" class="v-space-2x">
                  <h1 class="counter pull-right"><strong>{{ $counter->Task }}</strong></h1> 
                </div>       
            </div>      
          </div>  
        </a>
    </div>
  </div>                
@endsection

