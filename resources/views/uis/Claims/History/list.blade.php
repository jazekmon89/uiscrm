@extends('layouts.master-cmi')

@title('Current Claims')

@page_title('Current Claims')

{{-- Document/Body title --}}
@body_class("history-claims layout-box")

@cssblock('Claims.History.css.styles', 'all-styles')

@js('plugins/daterangepicker/moment.js', 'moment')

@jsblock('Claims.History.column-handler', 'claims-handler')

@section('body')
	<div class="row">
		<div class="col-md-12 col-md-offset-1 table-responsive">
			<div class="form-group">
				<div class="col-md-5">
					<select id="organisations" class="col-md-12">
						@foreach($organisations_with_claims as $i)
							<option value="{{ $i->OrganisationID }}">{{ $i->Name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-5">
					<select id="policies" class="col-md-12">
						{!! $policy_select_rendered !!}
					</select>
				</div>
			</div>
		</div>
		<div class="spacers-1">&nbsp;</div>
	</div>
	@include("Claims.History.preview")
	<div class="row">
		<div class="col-md-10 col-md-offset-1 table-responsive">
			<h4>Current Requested Claims</h4>
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
					@if($current_claims)
						@each("Claims.History.item", $current_claims, 'item')
					@endif
					<tr class="empty {{ $current_claims ? 'hidden' : '' }}"><td colspan="8" class="text-center">No current claims found.</td></tr>
				</tbody>
			</table>
			<h4>History of Requested Claims</h4>
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
@include('uis.layouts.footer.dashboard')
@endsection