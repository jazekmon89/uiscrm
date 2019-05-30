@extends('layouts.Backend')

{{-- Document title not page title --}}
@title('Request for Quotes')

{{-- Page title --}}
@page_title('JCI - Request for Quotes')

{{-- Document/Body title --}}
@body_class('rfq')

@section('content') 
<div class="bem-page__container-tabs bem-page__container-white bem-page__container-rounded">
	<ul class="nav nav-tabs">
	  <li role="presentation" class="active"><a href="#" data-toggle="tab"><span class="badge">1</span>Get A Quote</a></li>
	  <li role="presentation"><a href="#">Quote Form</a></li>
	</ul>
	<div class="grid-70 centered">
		@include('flash::message')
	</div>
	<div class="tab-content">
		<div class="tab-pane active" id="1">
		 	<div class="row">
				<div class="col-md-12 policy-list">
			 		<div class="row">
					@foreach($policies as $policy)
					<div class="bem-tabs__link-box col-md-4 {{ $policy->Name }}">
						<a href='{{route("quotes.form", [$policy->PolicyTypeID, $policy->FormTypeID, "OrganisationID" => $OrganisationID])}}' class="policy text-center"
							ionclick="window['rfq_form'] = this.href; return false;">
							<span>
								@if ($img = config('policytypes.'. $policy->PolicyTypeID .'.img.medium'))
									<img src="{{ $img }}" height="100">
								@endif
							</span>
							<h4 class="bem-title">{{ $policy->DisplayText }}</h4>
							@if($policy->Description)
								<span class="bem-tabs__description-text message">{{ $policy->Description }}</span>
							@elseif($desc = config('policytypes.'. $policy->PolicyTypeID .'.desc'))
								<span class="bem-tabs__description-text">{{ $desc }}</span>
							@endif
						</a>
					</div>
					@endforeach
					</div>
				</div>						
			</div>
		</div>
		<div class="tab-pane" id="2">
			<h3>Notice the gap between the content and tab after applying a background color</h3>
		</div>
	</div>
</div>
@endsection
<style>
	.div-for-admin {
		display:none;
	}
</style>
