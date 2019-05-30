@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Request for Quotes')

{{-- Page title --}}
@page_title('Request for Quotes')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red rfq')

@section('body')
<style>
	.policy-list h4 {margin: 15px auto!important;}
	.policy-list a{margin-bottom: 10px;}
</style>
<div id="cstm-tabs-default">
	<ul class="nav nav-tabs">
		<li class="active"><a  href="#1" data-toggle="tab"><span class="badge">1</span>Get A Quote</a></li>
		<li><a href="#" ><span class="badge">2</span>Quote Form</a></li>
	</ul>
		<div class="spacer">
		</div>
		<div class="grid-70 centered">
			@include('flash::message')
		</div>

		<div class="tab-content">
			<div class="tab-pane active" id="1">
				<div class = "row">
				<div class="col-md-12 policy-list">
					<div class="row">
					@foreach($policies as $policy)
					<div class="col-md-4 {{ $policy->Name }}">
						<a href='{{route("quotes.form", [$policy->PolicyTypeID, $policy->FormTypeID, "OrganisationID" => $OrganisationID])}}' class="policy text-center"
							ionclick="window['rfq_form'] = this.href; return false;">
								<span>
									@if ($img = config('policytypes.'. $policy->PolicyTypeID .'.img.medium'))
										<img src="{{ $img }}" height="100">
									@endif
								</span>
								<h4 class="title">{{ $policy->DisplayText }}</h4>
								@if($policy->Description)
									<span class="description message">{{ $policy->Description }}</span>
								@elseif($desc = config('policytypes.'. $policy->PolicyTypeID .'.desc'))
									<span class="description">{{ $desc }}</span>
								@endif
						</a>
					</div>
					@endforeach
					</div>
				</div>
				<!-- <div class="col-md-4 ">
					<div class="description-section">
					<h6 class="rp">READING PANE</h6>
					<h4 class="title">DESCRIPTION</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut hendrerit ante, sed feugiat ex. Integer ut mi eu sem dapibus suscipit. Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</p>
					<ul>
						<li>Public and Product Lialibilty</li>
						<li>Workers Compensation</li>
						<li>Marine Cargo</li>
					</ul>
      		<p>Etiam ornare faucibus odio vitae dignissim. Nulla laoreet eros lectus, vel hendrerit arcu scelerisque eu.</p>
				</div>
				<div class="form-group">
					<button data-link='' class="btn btn-primary btn-burgandy" onclick="if (typeof rfq_form !== 'undefined') location.href=rfq_form; else alert('You have to select a type of policy.'); return false;">Proceed with selection</button>
				</div>
			</div> -->
			</div>
		</div>
			<div class="tab-pane" id="2">
				<h3>Notice the gap between the content and tab after applying a background color</h3>
			</div>
		</div>

</div>
@endsection
