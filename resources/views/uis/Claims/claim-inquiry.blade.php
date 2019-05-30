@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Make a Claim - Claim Inquiry')

{{-- Page title --}}
@page_title('Claim Inquiry')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red')

{{-- Let Document know the css block we're trying to add --}}

@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@css('css/datetimepicker/bootstrap-datetimepicker.css', 'bootstrap-datetimepicker')


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
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')
@jsblock("uis.Claims.js.inquiry-scripts", "create_scripts", ['policy_policytype'=>$policy_policytype,'claim_type'=>$ClaimTypeID])

@push('nav-main-menu')
  <li class="list-group-item">{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
  <li class="list-group-item">{!! link_to_route('logout', "Log-out") !!}</li>
@endpush

@section('body')

<div id="cstm-tabs-default">
  <ul class="nav nav-tabs">
    <li><a href="{{ route('claim-request') }}"><span class="badge">1</span>Select Policy</a></li>
    <li class="active"><a href="#1" ><span class="badge">2</span>Claim Form</a></li>  
  </ul>
  <div class="spacers-1">&nbsp;</div>
  <div class="not-cstm-tabs-default panel-body offset1">
    @include('Claims.includes.claim-inquiry');
  </div>
</div>
<div>
  <div class="modal fade" id="notes-list" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body create-modal-body">
          {!! $notes_list_form !!}
          <div class="spinner spinner2" style="display:none;"></div>
        </div>
      </div>
    </div>
  </div>
</div>    
@endsection

