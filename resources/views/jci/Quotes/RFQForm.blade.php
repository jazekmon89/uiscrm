@extends(Auth::check() ? 'layouts.Frontend-client-jci-login' : 'layouts.Frontend')
@inject('Policy', 'App\Helpers\PolicyHelper')
@php $document->resetAssets() @endphp

{{-- Page Attributes --}}
@title("Request for $PolicyDisplayText Quote")
@page_title("Request for \"$PolicyDisplayText\" Quote")
@body_class('rfq')

{{-- assets are refresh to queue core files --}}
@css('css/app.css', 'app')
@js('js/app.js', 'app')

@section('content') 
<div class="bem-page__container-tabs bem-page__container-white bem-page__container-rounded">
	<ul class="nav nav-tabs">
	  <li role="presentation"><a href="{{ route('quotes.request', [$OrganisationID]) }}" ><span class="badge">1</span>Get A Quote</a></li>
	  <li role="presentation" class="active"><a href="#2" data-toggle="tab"><span class="badge">2</span>Quote Form</a></li>
	</ul>	
		<div class="tab-content">
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
				redirect_url += '?rfqrf=' + result.RFQRefNum;
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



