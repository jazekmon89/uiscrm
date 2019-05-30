@extends('layouts.master-cmi')

@title('Curret Quotes')

@page_title('Current Quotes')

{{-- Document/Body title --}}
@body_class("{$status}-quotes layout-box")

@js('plugins/daterangepicker/moment.js', 'moment')

@jsblock('Quotes.Index.quotes-handler', 'quotes-handler')

@section('body')
	@include("Quotes.Index.preview")

			<div class="row">
				<div class="col-md-12">

					<button class="btn btn-default btn-action" data-task='compareQuotes' >Compare Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					@if($status === 'current')
					<button class="btn btn-default btn-action" data-task='shareQuotes' >Share Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<button class="btn btn-default btn-action" data-task='expireQuotes' >Expire Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<button class="btn btn-default btn-action" data-task='uploadQuotes' >Upload Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<button class="btn btn-default btn-action" data-task='finalizeQuotes' >Finalize Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					@else
						<a class="btn btn-default" href='{{ route('quotes.request', [$OrganisationID]) }}' >New Quote <i class='cp-spinner cp-eclipse '></i></a>
					@endif
				</div>
			</div>
			<table class="table table-condensed table-hover table-striped Quotes {{ $status }}"'>
				<thead>
					<tr>
						<td>Select</td>
						<td>Date Submitted</td>
						<td>Quote NO.</td>
						<td>Insured Name</td>
						<td>Cover Start Date</td>
						<td>Cover End Date</td>
						<td>Underwriter</td>
						<td>Product</td>
						<td>Premium</td>
					</tr>	
				</thead>
				<tbody>
					@if($quotes)
						@each("Quotes.Index.item", $quotes, 'quote')
					@endif
					<tr class="empty {{ $quotes ? 'hidden' : '' }}"><td colspan="10" class="text-center">No Quotes found.</td></tr>
				</tbody>
			</table>
	

	<div id="compare-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Compare</h3>
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

	<div id="create-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">New Quote</h3>
				</div>		
				<div class="modal-body">
			    	
			  	</div>
			  	<div class="modal-footer">
				    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				</div>
		  	</div>
		</div>
	</div>
@endsection
