@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Request for Quotes')

{{-- Page title --}}
@page_title('Request for Quotes')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red rfq layout-box')

@section('body')
<div class="panel-body offset1 org-policies">
	<div class="row">
	  	<div class="col-md-12">   
			<div class="row top-buffer">   
            	@foreach($policies as $policy)						 
                    <div class="col-md-4" id="policy-{{ $policy->Name }}">                       
                        <div class="small-box">
                            <div class="inner">
                            <h3>{{$policy->DisplayText}}</h3>              

                            @if($policy->Description)
								<p>{{$policy->Description}}</p>
							@endif
                            </div>		                                    
                            <a href='{{route("quotes.form", [$policy->PolicyTypeID, $policy->FormTypeID, "OrganisationID" => $OrganisationID])}}' class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>  							
				@endforeach	
			</div>	
		</div>  			
	</div>
</div>					
@endsection

