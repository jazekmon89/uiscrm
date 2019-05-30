@extends('jci.layouts.Frontend-client-jci-login')

{{-- Document title not page title --}}
@title('Make a Claim - Claim Inquiry')

{{-- Page title --}}
@page_title('JCI - Claim Inquiry')

{{-- Document/Body title --}}
@body_class('rfq')

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')
@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@css('css/datetimepicker/bootstrap-datetimepicker.css', 'bootstrap-datetimepicker')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')
@jsblock("uis.Claims.js.inquiry-scripts", "create_scripts", ['policy_policytype'=>$policy_policytype,'claim_type'=>$ClaimTypeID])

@section('content')
<div class="bem-page__container-tabs bem-page__container-white bem-page__container-rounded"> 
  <ul class="nav nav-tabs">
    <li role="presentation"><a href="{{ route('claim-request') }}" data-toggle="tab"><span class="badge">1</span>Select Policy</a></li>
    <li role="presentation" class="active"><a href="#" ><span class="badge">2</span>Claim Form</a></li>
  </ul>
  <div class="tab-content">
    @include('Claims.includes.claim-inquiry');
  </div>
</div>
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
@endsection

