@php $old_data = Session::get('claim_old_data') @endphp
<div class="tab-pane active" id="1">
  <div class="row">
    <div class="col-md-12">
      @include('flash::message')
      {!! Form::open(['route' => ['claims-inquiry-create'],'files'=>true]) !!}
        {{ Form::jInput("hidden", "OrganisationID", $OrganisationID, ['id'=>'OrganisationID']) }}
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('ClaimTypeID')?'has-error':'' }}">
            {{ Form::label('Description', 'Type of claim:') }}
            {{ Form::jInput('select', "ClaimTypeID", $claim_types, $ClaimTypeID, ['id'=>'claim_types','class'=>'form-control']) }}
          </div>
        </div> 
        <div class="col-md-6"> 
          <div class="form-group {{ $errors->has('InsurancePolicyID')?'has-error':'' }}">
            {{ Form::label('Description', 'My Policy:') }}
            {{ Form::jInput('select', "InsurancePolicyID", $insurance_policies, $InsurancePolicyID.'/'.$PolicyTypeID, ['id'=>'policies', 'class'=>'form-control']) }}
          </div>
        </div>  
        <h5 class="bem-text_left">Policy Holder Details</h5>
        <div class="col-md-6"> 
          <div class="form-group">
            {{ Form::label('Description', 'Policy Holder Name') }}
            {{ Form::jInput('text', 'Policy_Holder_Name', $view_data['policyholdername'], ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>
        <div class="col-md-6">   
          <div class="form-group">
            {{ Form::label('Description', 'Policy Number') }}
            {{ Form::jInput('text', 'Policy_Number', $view_data['policynum'], ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>
        @if(false)
        <div class="col-md-6">   
          <div class="form-group">
            {{ Form::label('Description', 'Claim No') }}
            {{ Form::jInput('text', 'Claim_No', (isset($old_data['Claim_No'])?$old_data['Claim_No']:null), ['class' => 'form-control', 'placeholder'=>'N/A']) }}
          </div>
        </div>
        @endif
        <div class="col-md-6">   
          <div class="form-group">
            {{ Form::label('Description', 'Client Contact No') }}
            {{ Form::jInput('text', 'Client_Contact_No', $view_data['clientcontactno'], ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>  
        @if(false)
        <div class="col-md-12">
          <div class="form-group">
            {{ Form::label('Description', 'ABN') }}
            {{ Form::jInput('text', 'ABN', null, ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>  
        @endif
        <div class="col-md-12">
          <div class="form-group">
            {{ Form::label('Description', 'Are you registered for GST?') }}
            {{ Form::jInput('select', "GST", ['N'=>'No','Y'=>'Yes'], $view_data['gst'], ['class'=>'form-control GST', 'disabled'=>true, 'readonly'=>'readonly']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="GST-follow-up form-group {{ $errors->has('GST_Percentage')?'has-error':'' }}" style='display: none;'>
            {{ Form::label('Description', 'If so what percentage?') }}
            {{ Form::jInput('text', 'GST_Percentage', (isset($old_data['GST_Percentage'])?$old_data['GST_Percentage']:null), ['class' => 'form-control GSTPct']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::label('Description', 'Excess Amount') }}
            @php 
              $_rules = ['class' => 'form-control'];
              if(!empty($view_data['excess'])){
                $_rules['disabled'] = 'true';
                $_rules['readonly'] = 'readonly';
              }
            @endphp
            {{ Form::jInput('number', 'Excess_Amount', $view_data['excess'], $_rules) }}
          </div>
        </div>
        @if(false)
        <div class="col-md-12">  
          <div class="form-group {{ $errors->has('Additional_Contact_Name')?'has-error':'' }}">
            {{ Form::label('Description', 'Additional Contact Name') }}
            {{ Form::jInput('text', 'Additional_Contact_Name', (isset($old_data['Additional_Contact_Name'])?$old_data['Additional_Contact_Name']:null), ['class' => 'form-control']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group  {{ $errors->has('Contact_No')?'has-error':'' }}">
            {{ Form::label('Description', 'Contact No') }}
            {{ Form::jInput('text', 'Contact_No', (isset($old_data['Contact_No'])?$old_data['Contact_No']:null), ['class' => 'form-control']) }}
          </div>
        </div>
        @endif
        <h5 class="bem-text_left col-md-12">Claim Details</h5>
        <div class="col-md-12">
          <div class="form-group {{ $errors->has('Date_and_Time_of_Event')?'has-error':'' }}">
            <div class="input-group date">
              {{ Form::label('Description', 'Date and Time of Event') }}
              <div class="input-group input-append date" id="date_of_birth_cont">{{Form::jInput("datetime", "Date_and_Time_of_Event", (isset($old_data['Date_and_Time_of_Event'])?$old_data['Date_and_Time_of_Event']:null), ['id' => 'EventDatetime', 'class' => 'date form-control'])}}<span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span></div>
            </div>
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::label('Description', 'Description of Claim') }}
            {{ Form::textarea('Description_of_Claim', (isset($old_data['Description_of_Claim'])?$old_data['Description_of_Claim']:null), ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%']) }}
          </div>
        </div>  
        <h5 class="bem-text_left col-md-12">Insurer Details</h5>
        <div class="col-md-12">
          <div class="form-group">
            {{ Form::label('Description', 'Insurance Company') }}
            {{ Form::jInput('text', 'Insurance_Company', $view_data['insurancecompany'], ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::label('Description', 'Insurance Contact No') }}
            {{-- Form::jInput('tel', 'Insurance_Contact_No', (isset($old_data['Insurance_Contact_No'])?$old_data['Insurance_Contact_No']:null), ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) --}}
            {{ Form::jInput('tel', 'Insurance_Contact_No', $view_data['insurancecontactno'], ['class' => 'form-control', 'disabled'=>'true', 'readonly'=>'readonly']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group" >
            {{ Form::label('Description', 'Upload supporting documents (photos, proof of ownership, etc):') }}
            {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group" >
            {{ Form::label('Description', 'Additional Comments:') }}
            {{ Form::textarea('Additional_Comments', (isset($old_data['Additional_Comments'])?$old_data['Additional_Comments']:null), ['class' => 'form-control','rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
          </div>
        </div>  
        @if(Auth::user()->_is_adviser)
        <div class="col-md-12">
          <div class="form-group">
            <a href="#" id="notes_form" data-toggle="modal" data-target="#notes-list" class="btn btn-primary btn-flat">Notes</a>
          </div>
        </div>  
        @endif
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::jInput('checkbox', 'Confirmation', null, (isset($old_data['Additional_Comments']) && $old_data['Additional_Comments'] == 'on'?true:false), ['id'=>'Confirmation', 'class'=>'pull-left']) }}
            {{ Form::label('Confirmation', '&nbsp; I confirm that all the information provided is true and accurate.', ['class'=>'pull-left']) }}
          </div>
        </div> 
        <div class="col-md-12">  
          <div class="form-group"> 
            {{ Form::jInput('checkbox', 'Terms_and_Conditions', '', (isset($old_data['Terms_and_Conditions']) && $old_data['Terms_and_Conditions'] == 'on'?true:false), ['id'=>'termsconds', 'class'=>'pull-left']) }}
            <label for="termsconds" class="pull-left">&nbsp;  I have read and accept the <a href="#termsconds">terms and conditions</a>.</label>
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::label('Description', 'Digital Signature (Type whole name)') }}
            {{ Form::jInput('text', 'Digital_Signature', (isset($old_data['Digital_Signature'])?$old_data['Digital_Signature']:null), ['class'=>'form-control']) }}
          </div>
        </div>
        <div class="col-md-12">  
          <div class="form-group">
            {{ Form::submit('Submit Claim',['class' => 'btn btn-default btn-nolft-margin']) }}
          </div>
        </div>  
        {!! Form::close() !!}
      </div>
    </div> 
  </div> 
</div>