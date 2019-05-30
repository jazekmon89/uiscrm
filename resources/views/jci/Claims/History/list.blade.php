@extends('layouts.Frontend-client-jci-login')

@title('Current Claims')

@page_title('JCI - Current Claims')

{{-- Document/Body title --}}
@body_class("history-claims")

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')
@cssblock('Claims.History.css.styles', 'all-styles')

@js('plugins/daterangepicker/moment.js', 'moment')

@jsblock('Claims.History.column-handler', 'claims-handler')

@section('content')
<div class="bem-page__container-grid bem-page__container-white bem-form__container-rounded bem-container__center">
@if($no_policies)
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center">You don't have insurance policies as of now.</div>
			</div>
		</div>
	</div>
@else
	@if(!$policy_select_rendered)
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="text-center">You don't have insurance policies as of now.</div>
			</div>
		</div>
	</div>
	@else
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<select id="organisations" class="form-control grid-100">
						@foreach($organisations_with_claims as $i)
							<option value="{{ $i->OrganisationID }}">{{ $i->Name }}</option>
						@endforeach
					</select>
				</div>
			</div>				
			<div class="col-md-6">	
				<div class="form-group">
					<select id="policies" class="form-control grid-100">
						{!! $policy_select_rendered !!}
					</select>
				</div>
			</div>
		</div>
	</div>
	@include("Claims.History.preview")
	<div class="container-fluid">
		<div class="row">
			<div class="bem-heading__container-light-orange">
				<h5 class="bem-heading_light-orange bem-text_left">Current Requested Claims</h5>
			</div>	
			<div class="bem-table__container">				
				<table class="table table-hover table-striped table-responsive Claims history">
					<thead>
						<tr>
							<td>Reference Number</td>
							<td>Date Lodge</td>
							<td>Insurance Company</td>
							<td>Policy Name</td>
							<td>Consultant Name</td>
							<td>Status</td>
							<td>Excess</td>
							<td>Claimed Amount</td>
						</tr>	
					</thead>
					<tbody>
						@if($current_claims)
							@each("Claims.History.item", $current_claims, 'item')
						@endif
						<tr class="empty {{ $current_claims ? 'hidden' : '' }}"><td colspan="8" class="text-center">No current claims found.</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
	<div>&nbsp;</div>	
	<div class="container-fluid">
		<div class="row">
			<div class="bem-heading__container-light-orange">
				<h5 class="bem-heading_light-orange bem-text_left">History of Requested Claims</h5>
			</div>	
			<div class="bem-table__container table-responsive">					
				<table class="table table-condensed table-hover table-striped Claims history">
					<thead>
						<tr>
							<td>Reference Number</td>
							<td>Date Lodge</td>
							<td>Insurance Company</td>
							<td>Policy Name</td>
							<td>Consultant Name</td>
							<td>Status</td>
							<td>Excess</td>
							<td>Claimed Amount</td>
						</tr>	
					</thead>
					<tbody>
						@if($all_claims)
							@each("Claims.History.item", $all_claims, 'item')
						@endif
						<tr class="empty {{ $all_claims ? 'hidden' : '' }}"><td colspan="8" class="text-center">No current claims found.</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
@endif
</div>
@endsection