@extends('layouts.master-cmi', ['ClientID'=>$ClientID])

@title('Client Profiles - Quotes')

@page_title('<strong>' . $client->InsuredName ."</strong> - QUOTES")

{{-- Document/Body title --}}
@body_class('client-profiles-quotes')

@js('plugins/daterangepicker/moment.js', 'moment')

@jsblock('Client.Quotes.column-handler', 'client-quotes-handler')

@section('body')
	@include("Client.Quotes.preview")
	@include("Client.Quotes.notes")
	<div class="row">
		<div class="col-md-12 table-responsive">
			<h4>Quotes for: <b>{{ $client->InsuredName }}</b></h4>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-default btn-action" data-task='compareQuotes' >Compare Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<button class="btn btn-default btn-action" data-task='shareQuotes' >Share Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<button class="btn btn-default btn-action" data-task='expireQuotes' >Expire Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
					<a class="btn btn-default" data-toggle='modal' href="#upload-modal">Upload Quote(s) <i class='cp-spinner cp-eclipse '></i></a>
					<button class="btn btn-default btn-action" data-task='finalizeQuotes' >Finalize Quote(s) <i class='cp-spinner cp-eclipse '></i></button>
				</div>
			</div>
			<table class="table table-condensed table-hover table-striped Quotes current" data-client='{!! json_encode($client) !!}'>
				<thead>
					<tr>
						<td>Select</td>
						<td>Status</td>
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
					@if($current)
						@each("Client.Quotes.item", $current, 'quote')
					@endif
					<tr class="empty {{ $current ? 'hidden' : '' }}"><td colspan="10" class="text-center">No Quotes found.</td></tr>
				</tbody>
				
			</table>
			<table class="table table-condensed table-hover table-striped Quotes expired" data-client='{!! json_encode($client) !!}'>
				<caption>Expired quotes</b></caption>
				<thead>
					<tr>
						<td>Select</td>
						<td>Status</td>
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
					@if($expired)
						@each("Client.Quotes.item", $expired, 'quote')
					@endif
					<tr class="empty {{ $expired ? 'hidden' : '' }}"><td colspan="10" class="text-center">No Expired Quotes found.</td></tr>
				</tbody>
			</table>
		</div>
	</div>

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
	<div id="upload-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Upload Quotes</h3>
				</div>		
				<div class="modal-body">
					<div id="upload-quotes">
						{{ Form::open(['route' => ['client.upload-quotes', $ClientID], 'files' => true]) }}
							<div class="input-group row">
								<input type="text" class="form-control" disabled> 
								<div class="input-group-addon" style="position:relative;">
									<span>Browse</span>
									{{ Form::file('file', ['class' => 'btn col-md-6', 'style' => 'position:absolute;opacity: 0;left:0;top:0;width:100%;']) }}
								</div>
								<div class="input-group-btn">
									<button class="btn btn-default btn-primary btn-action" data-task="uploadQuotes">Import</button>
								</div>
									
								
							</div>
							
						{{ Form::close() }}
					</div>

					<div id="save-quotes">
						<div id="header-fields">
						<table class="table table-condensed table-hover table-striped">
				    		<thead>
				    			<tr>
				    				<th>Header</th>
				    				<th>Assign field</th>
				    			</tr>
				    		</thead>
				    		<tbody></tbody>
				    	</table>
				    	<button class="btn btn-primary btn-action" data-task="assignFields">Assign fields</button>
				    	</div>
						{{ Form::open(['route' => ['client.save-quotes', $ClientID], 'class' => 'hidden']) }}
					    	<table class="table table-condensed table-hover table-striped">
					    		<thead></thead>
					    		<tbody></tbody>
					    	</table>

					    	<button class="btn btn-primary btn-action" data-task="saveQuotes">Upload Quotes</button>
				    	{{ Form::close() }}
			    	</div>
			  	</div>
			  	<div class="modal-footer">
				    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				</div>
		  	</div>
		</div>
	</div>
@endsection
