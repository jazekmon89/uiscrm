@extends('jci.layouts.Frontend-client-jci-login')

{{-- Document title not page title --}}
@title('Make a Claim')

{{-- Page title --}}
@page_title('JCI - Make a Claim')

{{-- Document/Body title --}}
@body_class('rfq')

{{-- Let Document know the assets we're trying to add --}}
@css('css/app.css', 'app')
@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@cssblock("uis.Claims.css.styles",'all_styles')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@jsblock("uis.Claims.js.selection-scripts", "selection_scripts", ['link_claim_types'=>route('claim-types')])

@section('content')
<div class="bem-page__container-tabs bem-page__container-white bem-page__container-rounded">
	@include('flash::message')
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="#" data-toggle="tab"><span class="badge">1</span>Select a Policy</a></li>
		<li role="presentation" class="active"><a href="#"><span class="badge">2</span>Claim a Form</a></li>	
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="1">
			<div class="row">
				<div class="col-md-12">
			@if($no_policies)
					<div class="bem-tabs__link-box col-md-12">
						<div class="text-center">You don't have insurance policies as of now.</div>
					</div>
			@else
				@if(!$insurance_policies || (is_array($insurance_policies) && count($insurance_policies) == 0))
					<div class="bem-tabs__link-box col-md-12">
						<div class="text-center">You don't have insurance policies as of now.</div>
					</div>
				@else
					@foreach($insurance_policies as $k => $i)
					<div class="bem-tabs__link-box col-md-4">
						<a data-toggle="modal" data-target="#claim_selection" class="policy text-center" id="policy-{{ $i->DisplayText }}" data-id="{{ $k }}/{{ $i->PolicyTypeID }}">
							@if ($img = config('policytypes.'. $i->PolicyTypeID .'.img.medium'))
								<img src="{{ $img }}" height="100">
							@endif
							<h4 class="bem-title"><strong>{!!$i->DisplayText!!}</strong></h4>               
		                    @if (false) //$desc = config('policytypes.'. $i->PolicyTypeID .'.desc'))
							<span class="bem-tabs__description-text">{{$desc}}</span>
							@endif
						</a>
					</div>
					@endforeach
				@endif
				</div>
			@endif
			</div>
		</div>
		@if($no_policies == false)
		<div class="tab-pane" id="2">
			<h3>Notice the gap between the content and tab after applying a background color</h3>
		</div>
		@endif
	</div>
</div>
@if($no_policies == false)
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
@endif
@endsection

