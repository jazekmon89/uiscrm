@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.QuotesForm')

{{-- Document title not page title --}}
@title("Request for $PolicyDisplayText Quote")

{{-- Page title --}}
@page_title("Request for \"$PolicyDisplayText\" Quote")

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red rfq')

@php $document->resetAssets() @endphp

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('body')
<style>
	.form-tab-step a{
		color: #636b6f !important;
	}

	.form-tab-step.active a{
		color: #460000 !important;
	}
</style>
	<div id="cstm-tabs-default">
		<ul class="nav nav-tabs">
			<li><a  href="{{ route('quotes.request', [$OrganisationID]) }}" ><span class="badge">1</span>Get A Quote</a></li>
			<li class="active"><a href="#2" data-toggle="tab"><span class="badge">2</span>Quote Form</a></li>
		</ul>
			<div class="spacers-1">&nbsp;</div>
			<div class="tab-content ">
				<div class="tab-pane" id="1"></div>
				<div class="tab-pane active" id="2">
					<div class = "row">
						<div class="col-md-12">
							{!! $html !!}
						</div>
					</div>
				</div>
			</div>
	</div>

	<script>
		window.RFQFormCallback = function(result) {
			var redirect_url = '{{ route('quotes.request', [$OrganisationID]) }}';
			if (result.success) {
				redirect_url += '?rfq=' + result.RFQID;
				location.href = redirect_url;
			}
			else if (result.error) alert(result.error);			
			else alert("Ops! Some info is missing please review all forms then resubmit.");
		}
	</script>
	@if(Auth::check())
		<script>
			rfq_client.home_address = {!! json_encode(Auth::user()->home_address) !!};
			rfq_client.mail_address = {!! json_encode(Auth::user()->mail_address) !!};
			
			$(document).ready(function() {
				
				if (rfq_client.home_address)
					$('#home-address input').attr('readonly', false);
				if (rfq_client.mail_address)
				 	$('#mail-address input').attr('readonly', false);				
				$('#requester-fields input:not(#ContactDetails-RFQ-InsuredName)').attr('readonly', false);	
			});

		</script>
	@endif
@endsection



