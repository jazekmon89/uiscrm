@extends('layouts.master-cmi')

@title('Current Quotes')

@page_title('Current Quotes')

{{-- Document/Body title --}}
@body_class("history-quotes layout-box")

@js('plugins/daterangepicker/moment.js', 'moment')

@jsblock('Client.Quotes.column-handler', 'quotes-handler')

@section('body')
	@include("Quotes.History.preview")
	<div class="row">
		<div class="col-md-10 col-md-offset-1 table-responsive">
			<table class="table table-condensed table-hover table-striped Quotes hisrtory"'>
				<thead>
					<tr>
						<td>Reference Number</td>
						<td>Date Lodge</td>
						<td>Insurance Company</td>
						<td>Policy Name</td>
						<td>Consultant Name</td>
						<td>Status</td>
						<td>Excess</td>
						<td>Claimed</td>
					</tr>	
				</thead>
				<tbody>
					@if($rfqs)
						@each("Quotes.History.item", $rfqs, 'rfq')
					@endif
					<tr class="empty {{ $rfqs ? 'hidden' : '' }}"><td colspan="8" class="text-center">No Quote History found.</td></tr>
				</tbody>
			</table>
		</div>
	</div>
@include('uis.layouts.footer.dashboard')
@endsection
