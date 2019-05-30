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

<div class="panel-body offset1">
	<div class="row">
	  	<h3>Make a Claim</h3>
	  	<br />
		<h5>I would like a claim for:</h5>
			<div class="row top-buffer">
            	@foreach($insurance_policies as $policy_id=>$policy)
                <a data-toggle="modal" data-target="#claim_selection" role="button" class="col-md-3 small-box-wrapper" id="policy-{{ $policy['DisplayText'] }}" data-id="{{ $policy_id }}/{{ $policy['PolicyTypeID'] }}">
                	<div class="small-box-footer">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                            <h3 class="display-text">{!!$policy['DisplayText']!!}</h3>
                            @if(isset($policy['Description']))
								<p>{{$policy['Description']}}</p>
							@endif
                            </div>
                        </div>
                    </div>
                </a>
				@endforeach
			</div>
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


			
@include('uis.layouts.footer.dashboard')
@endsection

