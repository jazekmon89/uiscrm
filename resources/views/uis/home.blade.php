@extends('layouts.master-cmi')

@title('Dashboard')
@page_title('Just Coffee Insurance')
@body_class('dashboard')

{{-- Let Document know the assets we're trying to add --}}
@js("https://www.gstatic.com/charts/loader.js", "charts")
@jsblock("home-chart-script", "home-chart-script", compact('user_policies', 'Client'))

@section('body')
<!--   <div class="row">
    <div class="col-md-12"> -->
        <div class="row">
        	@if(false)
        	<!--div class="col-md-4 left">
        		@if (false && !empty($policies))
        		@foreach(array_chunk($policies, 2) as $client_policies)
        			<div class="row">
        			@foreach($client_policies as $user_policy)
        				@php $policyType = (object)array_get($user_policy, 'PolicyType') @endphp
        				<div class="col-md-{{ count($client_policies) > 1  ? '6' : '12'}}">
	        				<a class="small-box policies {{ $policyType->Name }}" href="#" data-policy='{{ json_encode($user_policy) }}'
	        					style="display:block;">	
	        					<div class="row">
	        						<div class="img col-md-3" style="padding-top: 15px;">
	        							<span class="img-placeholder" data-src='/images/policy/policy-{{ $policyType->Name }}.jpg'>
	        								<i class="fa fa-picture-o fa-2x"></i>
	        							</span>
										
									</div>
									<div class="col-md-9">
		        						<span class='title'>{{ $policyType->DisplayText }}</span>
		        						<span class="description">Lorem Ipsum {{ $policyType->Description }}</span>
		        						<span class="bottom">
			        						{{ array_get((array)$user_policy, "Underwriter.CompanyName") }} <b>/</b>
			        						{{ array_get((array)$user_policy, "PolicyNum", "N/A") }} <b>/</b>
			        						@dateFormat(array_get((array)$user_policy, "InsuranceQuote.ExpiryDateTime"))
		        						</span>
									</div>
								</div>	
	        				</a>
	        			</div>
        			@endforeach
        			</div>
		      	@endforeach
		      	@endif
		      	<div class="row">
		      		<div class="col-md-12">
        				<a class="small-box obtain-a-quote" href="{{ route("quotes.request") }}">
									<img src="/images/obtain-quote.png">
        					<div>
										<span class='title'>OBTAIN ANOTHER QUOTE</span>
      							<span class="description">Lorem ipsum dolor sit amet, consectetur</span>
									</div>
        				</a>
        			</div>
        			<div class="col-md-12">
        				<a class="small-box make-a-claim">
									<img src="/images/make-claim.png">
									<div>
										<span class='title'>MAKE A NEW CLAIM</span>
										<span class="description">Lorem ipsum dolor sit amet, consectetur</span>
									</div>
        				</a>
        			</div>
        			<div class="col-md-12">
        				<a class="small-box update-details">
									<img src="/images/update-details.png">
									<div>
										<span class='title'>UPDATE EXISTING DETAILS & CLAIMS</span>
										<span class="description">Lorem ipsum dolor sit amet, consectetur</span>
									</div>
        				</a>
        			</div>
		      	</div>
		    </div-->
		    @endif
		    <div class="col-md-12 right">
		    	<div class="row">
		    		<div class="col-md-12">
			    		<div class="small-box box-default" id="recommendation">
								<div class="title row">
			    					<h4 class="col-md-8">Recommended Insurance Policies FOR: </h4>
			    					<div class="col-md-4">
			    						<select class="form-control" onchange="location.href='{{ route('dashboard') }}' + this.value;">
			    							@foreach(arr_pairs($clients, "ClientID", "InsuredName") as $ClientID => $InsuredName)
			    							<option value="{{ $ClientID }}">{{ $InsuredName }}</option>
			    							@endforeach	
			    						</select>	
			    					</div>
								</div>
			    			<div id="wheel-container" class="row">
			    				<div class="col-md-12 text-center">
				    				<span id='piechart_before' class="hidden"></span>
									<span id='piechart_after' class="hidden"></span>
									<br>
									<span id='piechart_diff'></span>
								</div>
			    			</div>
			    			<div id="disclaimer-desc">
			    				<h4>Disclaimer:</h4>
			    				<p>The following information is a guide only and this recommendation is only an indication of what likely covers you may need for the type of business type/occupation selected. There may be other policies that you need or require. These will be considered based on your circumstances and situation and what other policies you currently have in place.
			    				</p>
									<!-- <a class="read-more">Read More &raquo;</a> -->

			    			</div>
			    		</div>
		    		</div>
		    	</div>

		    </div>
      	</div>
   <!--  </div>
  </div> -->

  	<div id="compare-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Acknowledgement & Compare</h3>
				</div>
				<div class="modal-body">
			    	<table class="table table-condensed table-hover table-striped">
			    		<thead></thead>
			    		<tbody></tbody>
			    	</table>
			  	</div>
			  	<div class="modal-footer">
				    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				</div>
		  	</div>
		</div>
	</div>

	<div id="acknowledgement-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Acknowledgement</h3>
				</div>
				<div class="modal-body">
			    	Lorem ipsum dolor met summit!
			  	</div>
			  	<div class="modal-footer">
				    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				</div>
		  	</div>
		</div>
	</div>

@endsection
