<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        {!! Form::open() !!}
          {{ Form::jInput("hidden", "OrganisationID", $OrganisationID, ['id'=>'OrganisationID']) }}
        @if(Auth::user()->_is_adviser)
        <div class="form-group">        
          {{ Form::label('Description', 'Insured Name:') }}
          {{ Form::jInput('text', "insured_name", $InsuredName, null, ['class'=>'form-control']) }}
        </div>
        <div class="form-group">  
          {{ Form::label('Description', 'Company Name:') }}
          {{ Form::jInput('text', "company_name", $CompanyName, null, ['class'=>'form-control']) }}
        </div>  
        @endif
        <div class="form-group"> 
          {{ Form::label('Description', 'Insurance Policy:') }}
          <select class="form-control policies" id="policies" name="policies">
            @foreach($insurance_policies as $k=>$i)
            <option value="{{ $k }}" data-polid="{{ $k }}/{{ $i['PolicyTypeID'] }}">{{ $i["DisplayText"] }}</option>
            @endforeach
          </select>
         </div>
        <div class="form-group">  
          {{ Form::label('Description', 'Type of claim:') }}
          {{ Form::jInput('select', "claim_type", [], null, ['class' => 'form-control claim_type','id'=>'claim_type']) }}
        </div>
        <div class="form-group" >
          <a href="{{ url('/claims/inquiry/form/') }}" role="button" id="submit-claim-selection" class='submit btn btn-default btn-nolft-margin'> Continue to Claim</a>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
    </div>
  </div>  
</div>