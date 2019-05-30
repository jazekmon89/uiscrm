@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Dashboard')

{{-- Page title --}}
@page_title('UIS CRM - Dashboard')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red client-profiles layout-full')

@section('body')           
  <div class="row">          
    <!-- <div class="col-md-12 col-no-padding">    
          {{Form::open(['route' => ['search.details', 'FindInsuranceEntitiesByInsuranceDetails']])}}
            <div class='row'>
                <div class="col-lg-1 col-md-6 form-group">
                  <div class="input-group">
                    {{Form::label('search', 'Search For:')}}
                  </div>
                </div>  
                <div class="col-lg-3 col-md-6 form-group">
                  <div class="input-group">
                    {{Form::text('PolicyNum', null, ['placeholder' => 'Policy No.', 'class' => 'form-control'])}}
                    <span class="input-group-btn">
                      <button class="btn"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 form-group">
                  <div class="input-group">
                    {{Form::text('InvoiceNum', null, ['placeholder' => 'Invoice No.', 'class' => 'form-control'])}}
                    <span class="input-group-btn">
                      <button class="btn"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </div>
                <div class="col-lg-5 col-md-6 form-group">
                  <div class="input-group">
                    {{Form::text('InsuredName', null, ['placeholder' => 'Insured name','class' => 'form-control'])}}
                    <span class="input-group-btn">
                      <button class="btn"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </div>
            </div>
        {{Form::close()}}                 
    </div>
    <div class="col-md-12 col-no-padding">
      <div class="spacers-1">&nbsp;</div>
      <div class="divider">&nbsp;</div>
      <div class="spacers-1">&nbsp;</div>
    </div> -->
    <div class="col-md-12 col-no-padding">
      <div class="row top-buffer">  
          <a href="{{ route('client.profiles') }}" class="small-box">
            <div class="col-lg-4 col-md-6">                       
                <div class="menu-box">                
                    <span class="img-container">
                      <img src="/images/insurance-interface/clients.gif">
                    </span>
                    <h4 class="title"><strong>Clients</strong></h4>           
                    <!-- <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span> -->             
                </div>
            </div>
          </a> 
          <a ihref="{{ route('quotes.index') }}" class="small-box disabled"> 
            <div class="col-lg-4 col-md-6">
                <div class="menu-box">                  
                  <span class="img-container">
                    <img src="/images/insurance-interface/quotes.gif">
                  </span>
                  <h4 class="title"><strong>Quotes</strong></h4>               
                  <!-- <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span>  --> 
                </div>           
            </div> 
          </a> 
          <a href="#" class="small-box disabled">  
          <!--<a href="{{ route('claims.index') }}" class="small-box-footer">-->
            <div class="col-lg-4 col-md-6">
              <div class="menu-box">
                  <span class="img-container">
                    <img src="/images/insurance-interface/policies.gif">
                  </span>
                  <h4 class="title"><strong>Policies</strong></h4>               
                  <!-- <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span> -->
              </div>      
            </div>  
          </a> 
          <a href="#" class="small-box disabled">  
          <!--<a href="{{ route('claims.index') }}" class="small-box-footer">-->
            <div class="col-lg-4 col-md-6">
              <div class="menu-box">               
                  <span class="img-container">
                    <img src="/images/insurance-interface/claims.gif">
                  </span>                  
                    <h4 class="title"><strong>Claims</strong></h4>
                  <!-- <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span> -->
              </div>      
            </div>  
          </a> 
         <!--  <a href="#" class="small-box disabled">  -->
          <!--<a href="{{ route('renewals.index') }}" class="small-box-footer">-->                       
            <!-- <div class="col-lg-4 col-md-6">
              <div class="menu-box">
                  <span class="img-container">
                    <img src="/images/insurance-interface/current-renewals.png">
                  </span>
                  <h4 class="title"><strong>Current Renewals</strong></h4>               
                  <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span>
              </div>            
            </div> 
          </a> -->
          <!-- <a href="#" class="small-box disabled">  -->
          <!--<a href="{{ route('lodges.index') }}" class="small-box-footer">-->
          <!-- <div class="col-lg-4 col-md-6">
            <div class="menu-box">
                <span class="img-container">
                  <img src="/images/insurance-interface/lodge-claim.png">
                </span>
                <h4 class="title"><strong>Lodge Claims</strong></h4>               
                <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span>                
            </div>            
          </div> 
          </a> -->
          <a href="#" class="small-box disabled"> 
          <!--<a href="{{ route('actions.index') }}" class="small-box-footer">-->
            <div class="col-lg-4 col-md-6">
              <div class="menu-box">                  
                  <span class="img-container">
                    <img src="/images/insurance-interface/action-items.gif">
                  </span>
                  <h4 class="title"><strong>Action Items</strong></h4>               
                  <!-- <span class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</span>    -->     
              </div>             
            </div> 
          </a>
      </div>
    </div>
  </div>                
@endsection

