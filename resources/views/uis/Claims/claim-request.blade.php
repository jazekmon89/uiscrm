@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Make a Claim')

{{-- Page title --}}
@page_title('Make a Claim')

{{-- Let Document know the css block we're trying to add --}}

@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@cssblock("uis.Claims.css.styles",'all_styles')


@push('header-css-blocks')
<style type="text/css">
	.org-policies.row a {
		height: 150px;
		background: #fff;
		border: 1px solid #eee;
		margin-bottom: 2.33333333%;
		margin-top: 2.33333333%;	
	}
	.org-policies.row a input {
		visibility: hidden;
		position: absolute;
	}
</style>
@endpush

{{-- Let Document know the js block we're trying to add --}}

@js('js/app.js', 'app')
@jsblock("uis.Claims.js.selection-scripts", "selection_scripts", ['link_claim_types'=>route('claim-types')])

@push('nav-main-menu')
  <li class="list-group-item">{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
  <li class="list-group-item">{!! link_to_route('logout', "Log-out") !!}</li>
@endpush

@section('body')
<div id="cstm-tabs-default">
	@include('flash::message')
	<ul class="nav nav-tabs">
		<li class="active"><a  href="#1" data-toggle="tab"><span class="badge">1</span>Select Policy</a></li>
		<li><a href="#" ><span class="badge">2</span>Claim Form</a></li>	
	</ul>
	<div class="spacer">
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="1">
				<div class="row">
					<div class="col-md-12">
						@foreach($insurance_policies as $policy_id=>$policy)
						<div class="col-md-4">
							<a data-toggle="modal" data-target="#claim_selection" class="policy text-center" id="policy-{{ $policy['DisplayText'] }}" data-id="{{ $policy_id }}/{{ $policy['PolicyTypeID'] }}">
								@if ($img = config('policytypes.'. $policy['PolicyTypeID'] .'.img.medium'))
									<img src="{{ $img }}" height="100">
								@endif
								<h4 class="title"><strong>{!!$policy['DisplayText']!!}</strong></h4>               
			                    @if (false) //$desc = config('policytypes.'. $policy['PolicyTypeID'] .'.desc'))
									<span class="description">{{$desc}}</span>
								@endif
							</a>
						</div>
						@endforeach
					</div>
					<div class="hide col-md-4 ">
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
					</div>
				</div>
			</div>
			<div class="tab-pane" id="2">
				<h3>Notice the gap between the content and tab after applying a background color</h3>
			</div>
		</div>
	</div>
	<div class="modal fade" id="claim_selection" tabindex="-1" role="dialog" style="display: none;">
		<div class="modal-dialog modal-lg" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body create-modal-body">
	      			{!! $selection_form !!}
	        		<div class="spinner spinner2" style="display:none;">
	        		</div>
	      		</div>
	    	</div>
	  	</div>
	</div>	
</div>		
@endsection

