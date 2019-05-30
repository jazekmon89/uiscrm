@extends('layouts.Frontend-client-jci-login')

{{-- Document title not page title --}}
@title('Make a Claim - Motor Vehicle CLaim')

{{-- Page title --}}
@page_title('JCI - Motor Vehicle Claim')

{{-- Document/Body title --}}
@body_class('rfq')

{{-- Let Document know the assets we're trying to add --}}
@css('css/app.css', 'app')
@css('css/rfq-forms.css', 'rfq')
@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@css('css/datetimepicker/bootstrap-datetimepicker.css', 'bootstrap-datetimepicker')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')
@jsblock("uis.Claims.js.inquiry-scripts", "create_scripts", ['policy_policytype'=>$policy_policytype,'claim_type'=>$ClaimTypeID])
@jsblock("Claims.js.motor-vehicle-scripts", "motor_scripts")

@section('content')
<div class="bem-page__container-tabs bem-page__container-white bem-form__container-rounded bem-container__center"> 
  <ul class="nav nav-tabs">
    <li role="presentation"><a href="#" data-toggle="tab"><span class="badge">1</span>Get A Claim</a></li>
    <li role="presentation" class="active"><a href="#"><span class="badge">2</span>Claim Form</a></li>
  </ul>    
  <div class="not-cstm-tabs-default panel-body offset1">
    @include('Claims.includes.claim-motor-vehicle');
  </div>
</div>
<div class="panel-body offset1">
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
