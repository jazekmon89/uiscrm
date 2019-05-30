@extends('layouts.Frontend-client-jci-login')

{{-- Document title not page title --}}
@title('Dashboard')

{{-- Page title --}}
@page_title('JCI - Dashboard')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the assets we're trying to add --}}
@css('css/app.css', 'app')

@js("https://www.gstatic.com/charts/loader.js", "charts")
@jsblock("home-chart-script", "home-chart-script", compact('user_policies', 'Client'))

@section('content')
@if(arr_pairs($clients, "ClientID", "InsuredName"))
<div class="bem-page__container-grid bem-page__container-white bem-form__container-rounded bem-container__center">                    
    <div id="recommendation">
		
		<div class="row bem-module__heading-text">
			<div class="col-md-8">
				<h4>Recommended Insurance Policies FOR:</h4>
			</div>
			<div class="col-md-4 pull-right">
				<select class="form-control" onchange="location.href='{{ route('dashboard') }}' + this.value;">
					@foreach(arr_pairs($clients, "ClientID", "InsuredName") as $ClientID => $InsuredName)
					<option value="{{ $ClientID }}">{{ $InsuredName }}</option>
					@endforeach	
				</select>	
			</div>
		</div>	
		<div class="text-center">
			<div id="wheel-container" class="disp-inline-table centered">			
				<span id='piechart_before' class="hidden bem-piechart__text"></span>
				<span id='piechart_after' class="hidden bem-piechart__text"></span>
				<span id='piechart_diff' class="bem-piechart__text"></span>			
			</div>
		</div>
		<div id="disclaimer-desc">
			<h4>Disclaimer:</h4>
			<p>The following information is a guide only and this recommendation is only an indication of what likely covers you may need for the type of business type/occupation selected. There may be other policies that you need or require. These will be considered based on your circumstances and situation and what other policies you currently have in place.
			</p>
		</div>
	</div>  
</div>	                                       
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
@endif
@endsection
