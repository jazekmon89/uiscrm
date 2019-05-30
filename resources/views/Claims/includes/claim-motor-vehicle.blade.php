<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        {!! Form::open(['route' => ['claims-motor-vehicle-create','files'=>true]]) !!}
          <div>
          <div class="form-group col-md-4" >
            {{ Form::label('Description', 'Type of claim:') }}
            {{ Form::jInput('select', "ClaimType", $claim_types, $ClaimTypeID, ['class'=>'form-control']) }}
          </div>
          <div class="form-group col-md-4" >
            {{ Form::label('Description', 'My Policy:') }}
            {{ Form::jInput('select', "InsurancePolicyID", $insurance_policies_minified, $InsurancePolicyID.'/'.$PolicyTypeID, ['id'=>'policies', 'class'=>'form-control']) }}
          </div>
          <div class="form-group col-md-4" >
            {{ Form::label('Description', 'Vehicle Registration:') }}
            {{ Form::jInput('select', "VehicleRegistration", [], '', ['class'=>'form-control']) }}
          </div>
          </div>
          <!--<div class="" id="policy-holder-accordion">-->
            <div> <!--breadcrumb-->
              <ul class="cd-breadcrumb triangle">
                <li class="current"><a id="policy-holder-info">Policy Holder Information</a></li>
                <li><a id="third-party-info">Third Party Information</a></li>
                <li><a id="witness-report">Witness Report</a></li>
                <li><a id="policy-report">Policy Report</a></li>
                <li><a id="submit-claim">Submit Claim</a></li>
              </ul>
            </div>

            <div class="breadcrumb-content">
              <div class="breadcrumb-content-div" id="policy-holder-info">
                
                <!--policy holder content-->
                <div class="panel panel-default">
                    <div clss="panel-heading">
                      <h4 class="panel-title label">
                        <a data-parent="#policy-holder-accordion" data-toggle="collapse" href="#policy-holder">Policy Holder Details</a>
                      </h4>
                    </div>
                    <div id="policy-holder" class="panel-collapse">
                      <div class="panel-body row">
                        <div class="form-group">
                          {{ Form::label('Description', 'Policy Holder Name') }}
                          {{ Form::jInput('text', 'PolicyHolderName', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                          {{ Form::label('Description', 'Policy Number') }}
                          {{ Form::jInput('text', 'PolicyNumber', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">  
                          {{ Form::label('Description', 'Claim No') }}
                          {{ Form::jInput('text', 'ClaimNo', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                          {{ Form::label('Description', 'Client Contact No') }}
                          {{ Form::jInput('text', 'ClientContactNo', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group"> 
                          {{ Form::label('Description', 'ABN') }}
                          {{ Form::jInput('text', 'ABN', null, ['class' => 'form-control']) }}
                        </div> 
                        <div class="form-group">
                          {{ Form::label('Description', 'Are you registered for GST?') }}
                          {{ Form::jInput('select', "GST", ['N'=>'No','Y'=>'Yes'], null, ['class'=>'form-control GST']) }}
                        </div>  
                        <div class="GST-follow-up" style='display: none;'>
                          {{ Form::label('Description', 'If so what percentage?') }}
                          {{ Form::jInput('text', 'GSTPct', null, ['class' => 'form-control GSTPct']) }}
                        </div>
                        <div class="form-group">
                          {{ Form::label('Description', 'Excess Amount') }}
                          {{ Form::jInput('number', 'ExcessAmount', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="panel-body row">
                          {{ Form::label('Description', 'Additional Contact Name') }}
                          {{ Form::jInput('text', 'AdditionalContactName', null, ['class' => 'form-control']) }}
                          {{ Form::label('Description', 'Contact No') }}
                          {{ Form::jInput('text', 'ContactNo', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="panel-body row">
                          <div class="form-group">                                       
                              <ul class="list-inline">
                                  <li class="pull-right"><button data-toggle="collapse" href="#claim-details" class="btn btn-primary btn-flat btn-burgundy step-forward">Next</button></li>
                              </ul>                                       
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div clss="panel-heading">
                      <h4 class="panel-title label">
                        <a data-parent="#claim-details-accordion" data-toggle="collapse" href="#claim-details">Incident Details</a>
                      </h4>
                    </div>
                    <div id="claim-details" class="panel-collapse collapse">
                      <div class="panel-body row">
                        
                        <div class="input-group date">
                          {{ Form::label('Description', 'Date and Time of Event') }}
                          {{Form::jInput("datetime", "EventDatetime", null, ['id' => 'EventDatetime', 'class' => 'form-control'])}}
                        </div>

                        {{ Form::label('Description', 'Description of Claim') }}
                        {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%']) }}
                      </div>
                      <div class="panel-body row">
                        <div class="form-group">                                       
                            <ul class="list-inline">
                                <li class="pull-left"><button data-toggle="collapse" href="#claim-details" class="btn btn-primary btn-flat btn-burgundy next-back">Previous</button></li>
                                <li class="pull-right"><button data-toggle="collapse" href="#insurer-details" class="btn btn-primary btn-flat btn-burgundy step-forward">Next</button></li>
                            </ul>                                       
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div clss="panel-heading">
                      <h4 class="panel-title label">
                        <a data-parent="#insurer-details-accordion" data-toggle="collapse" href="#insurer-details">Insurer Details</a>
                      </h4>
                    </div>
                    <div id="insurer-details" class="panel-collapse collapse">
                      <div class="panel-body row">
                        {{ Form::label('Description', 'Insurance Company') }}
                        {{ Form::jInput('text', 'InsuranceCompany', null, ['class' => 'form-control']) }}
                        {{ Form::label('Description', 'Insurance Contact No') }}
                        {{ Form::jInput('text', 'InsuranceContactNo', null, ['class' => 'form-control']) }}
                      </div>
                      <div class="panel-body row">
                        <div class="form-group">                                       
                            <ul class="list-inline">
                                <li class="pull-left"><a data-toggle="collapse" href="#insurer-details" class="btn btn-primary btn-flat btn-burgundy next-back">Previous</a></li>
                            </ul>                                       
                        </div>
                      </div>
                    </div>
                  </div>

                  <p>{{ Form::checkbox('policyHolderCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyHolderCheckbox1']) }}
                  {{ Form::label('policyHolderCheckbox1', 'Was a third party vehicle involved?') }}</p>
                  <p>{{ Form::checkbox('policyHolderCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyHolderCheckbox2']) }}
                  {{ Form::label('policyHolderCheckbox2', 'Was a witness present?') }}</p>
                  <p>{{ Form::checkbox('policyHolderCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyHolderCheckbox3']) }}
                  {{ Form::label('policyHolderCheckbox3', 'Was a police report lodged?') }}</p>

                  <div class="form-group" >
                    {{ Form::label('Description', 'Attachment:') }}
                    {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                  </div>
                  <div class="form-group" >
                    {{ Form::label('Description', 'Additional Comments:') }}
                    {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
                  </div>
                  @if(true || Auth::user()->_is_adviser)
                  <div class="form-group">
                    <button id="notes_form" class="btn btn-primary btn-flat">Notes</button>
                  </div>
                  @endif

                  <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6 text-right">
                      <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="third-party-info">Next</button>
                    </div>
                  </div>

                </div> <!-- /panel-default-->
              </div> <!-- /policy-holder-->
              <div class="breadcrumb-content-div hide" id="third-party-info">

                <h4>Third Party Information</h4>
                
                {{ Form::label('Description', 'Third Party Driver Name') }}
                {{ Form::jInput('text', 'ThirdPartyDriverName', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', "Driver's Licence") }}
                {{ Form::jInput('text', 'DriversLicence', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', "Driver's Address") }}
                {{ Form::jInput('text', 'DriversAddress', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', "Driver's Date of Birth") }}
                {{ Form::jInput("datetime", "DriversDateofBirth", null, ['id' => 'DriversDateofBirth', 'class' => 'form-control datepicker'])}}
                {{ Form::label('Description', "Driver's Contact Number") }}
                {{ Form::jInput('text', 'DriversContactNumber', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', "Driver's Insurer") }}
                {{ Form::jInput('text', 'DriversInsurer', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Vehicle Registration') }}
                {{ Form::jInput('text', 'VehicleRegistration', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Description of vehicle or property damaged') }}
                {{ Form::jInput('text', 'DescriptionOfVehicleOrPropertyDamaged', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Location and description of damage') }}
                {{ Form::jInput('text', 'LocationAndDescriptionOfDamage', null, ['class' => 'form-control']) }}


                <p>{{ Form::checkbox('thirdPartyCheckbox[]', 1, null, ['class' => 'field', 'id' => 'thirdPartyCheckbox1']) }}
                {{ Form::label('thirdPartyCheckbox1', 'Was a third party vehicle involved?') }}</p>
                <p>{{ Form::checkbox('thirdPartyCheckbox[]', 1, null, ['class' => 'field', 'id' => 'thirdPartyCheckbox2']) }}
                {{ Form::label('thirdPartyCheckbox2', 'Was a witness present?') }}</p>
                <p>{{ Form::checkbox('thirdPartyCheckbox[]', 1, null, ['class' => 'field', 'id' => 'thirdPartyCheckbox3']) }}
                {{ Form::label('thirdPartyCheckbox3', 'Was a police report lodged?') }}</p>

                <div class="form-group" >
                  {{ Form::label('Description', 'Attachment:') }}
                  {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                </div>
                <div class="form-group" >
                  {{ Form::label('Description', 'Additional Comments:') }}
                  {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
                </div>
                @if(true || Auth::user()->_is_adviser)
                <div class="form-group">
                  <button id="notes_form" class="btn btn-primary btn-flat">Notes</button>
                </div>
                @endif

                <div class="row">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="policy-holder-info">Previous</button>
                  </div>
                  <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="witness-report">Next</button>
                  </div>
                </div>


              </div>
              <div class="breadcrumb-content-div hide" id="witness-report">
                 <h4>Witness Information</h4>

                {{ Form::label('Description', 'Witness Name') }}
                {{ Form::jInput('text', 'WitnessName', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Witness Contact Number') }}
                {{ Form::jInput('text', 'WitnessContactNumber', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Witness Address') }}
                {{ Form::jInput('text', 'WitnessAddress', null, ['class' => 'form-control']) }}

                <p>{{ Form::checkbox('witnessReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'witnessReportCheckbox1']) }}
                {{ Form::label('witnessReportCheckbox1', 'Was a third party vehicle involved?') }}</p>
                <p>{{ Form::checkbox('witnessReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'witnessReportCheckbox2']) }}
                {{ Form::label('witnessReportCheckbox2', 'Was a witness present?') }}</p>
                <p>{{ Form::checkbox('witnessReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'witnessReportCheckbox3']) }}
                {{ Form::label('witnessReportCheckbox3', 'Was a police report lodged?') }}</p>

                <div class="form-group" >
                  {{ Form::label('Description', 'Attachment:') }}
                  {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                </div>
                <div class="form-group" >
                  {{ Form::label('Description', 'Additional Comments:') }}
                  {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
                </div>
                @if(true || Auth::user()->_is_adviser)
                <div class="form-group">
                  <button id="notes_form" class="btn btn-primary btn-flat">Notes</button>
                </div>
                @endif

                <div class="row">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="third-party-info">Previous</button>
                  </div>
                  <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="policy-report">Next</button>
                  </div>
                </div>

              </div>
              <div class="breadcrumb-content-div hide" id="policy-report">

                <h4>Policy Information</h4>

                {{ Form::label('Description', 'Police Report Number') }}
                {{ Form::jInput('text', 'PoliceReportNumber', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Any drugs or alcohol consumed 24 hrs prior to incident') }}
                {{ Form::jInput('text', 'AnyDrugsOrAlcoholConsumed', null, ['class' => 'form-control']) }}
                {{ Form::label('Description', 'Was the police present?') }}
                {{ Form::jInput('select', "policePresent", ['N'=>'No','Y'=>'Yes'], null, ['class'=>'form-control policePresent']) }}
                {{ Form::label('Description', 'Was anyone injured?') }}
                {{ Form::jInput('select', "anyoneInjured", ['N'=>'No','Y'=>'Yes'], null, ['class'=>'form-control anyoneInjured']) }}

                <p>{{ Form::checkbox('policyReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyReportCheckbox1']) }}
                {{ Form::label('policyReportCheckbox1', 'Was a third party vehicle involved?') }}</p>
                <p>{{ Form::checkbox('policyReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyReportCheckbox2']) }}
                {{ Form::label('policyReportCheckbox2', 'Was a witness present?') }}</p>
                <p>{{ Form::checkbox('policyReportCheckbox[]', 1, null, ['class' => 'field', 'id' => 'policyReportCheckbox3']) }}
                {{ Form::label('policyReportCheckbox3', 'Was a police report lodged?') }}</p>

                <div class="form-group" >
                  {{ Form::label('Description', 'Attachment:') }}
                  {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                </div>
                <div class="form-group" >
                  {{ Form::label('Description', 'Additional Comments:') }}
                  {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
                </div>
                @if(true || Auth::user()->_is_adviser)
                <div class="form-group">
                  <button id="notes_form" class="btn btn-primary btn-flat">Notes</button>
                </div>
                @endif

                <div class="row">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="witness-report">Previous</button>
                  </div>
                  <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="submit-claim">Next</button>
                  </div>
                </div>

              </div>
              <div class="breadcrumb-content-div hide" id="submit-claim">

                  {{ Form::label('Description', 'In the post 3 years, has the policy holder or the driver in this incident either: ', ['class'=>'col-md-12']) }}
                  <ul>
                    <li>&nbsp;</li>
                    <li>{{ Form::label('Description', 'Had their licence cancelled, disqualified or suspended?') }}</li>
                    <li>{{ Form::label('Description', 'Been convicted, or had any fines or penalties imposed for any alcohol related driving offences or crime involving drugs, dishonesty, arson, theft or violence against any person or property?') }}</li>
                  </ul>

                  {{ Form::checkbox('pastYears[]', 1, null, ['class' => 'field', 'id' => 'pastYears1']) }}
                  {{ Form::label('pastYears1', 'No') }}
                  {{ Form::checkbox('pastYears[]', 1, null, ['class' => 'field', 'id' => 'pastYears2']) }}
                  {{ Form::label('pastYears2', 'Yes, please describe below:') }}
                  {{ Form::jInput('textarea', 'pastYearsText', null, ['rows'=>'3', 'class' => 'form-control']) }}

                  {{ Form::label('Description', 'In the past 5 years, has the policy holder or the driver in this incident had an insurance policy declined, cancelled or conditions imposed on an insurance policy?', ['class'=>'col-md-12']) }}

                  {{ Form::checkbox('past5Years[]', 1, null, ['class' => 'field', 'id' => 'past5Years1']) }}
                  {{ Form::label('past5Years1', 'No') }}
                  {{ Form::checkbox('past5Years[]', 1, null, ['class' => 'field', 'id' => 'past5Years2']) }}
                  {{ Form::label('past5Years2', 'Yes, please describe below:') }}
                  {{ Form::jInput('textarea', 'pastYearsText', null, ['rows'=>'3', 'class' => 'form-control']) }}

                  <div class="form-group" >
                    {{ Form::label('Description', 'Attachment:') }}
                    {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                  </div>
                  <div class="form-group" >
                    {{ Form::label('Description', 'Additional Comments:') }}
                    {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%', 'id'=>"add_comments"]) }}
                  </div>

                  <button type="button" class="btn btn-primary btn-flat" id="policy-report">Correspondance</button>

                  <p>{{ Form::checkbox('confirmation', 1, null, ['class' => 'field', 'id' => 'confirmation']) }}
                  {{ Form::label('confirmation', 'I confirm that all the information provided is true and accurate.') }}</p>
                  <p>{{ Form::checkbox('termsconds', 1, null, ['class' => 'field', 'id' => 'termsconds']) }}
                  {{ Form::label('termsconds', 'I have read and accept the <a href="#">terms and conditions</a>.') }}</p>

                  {{ Form::label('Description', 'Digital Signature (Type whole name)', ['class'=>'col-md-12']) }}
                  {{ Form::jInput('text', 'DigitalSignature', null, ['class' => 'form-control']) }}


                <div class="row">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-primary btn-flat btn-burgundy btn-breadcrumb-trigger" id="policy-report">Previous</button>
                  </div>
                  <div class="col-lg-6 text-right">
                    {{ Form::submit('Submit Claim',['class' => 'btn btn-default btn-nolft-margin']) }}
                  </div>
                </div>

              </div>
            </div><!--/breadcrumb-content-->

          </div>  <!-- /panel-body-->
       
        {!! Form::close() !!}
      </div>
    </div>
    </div>
  </div>  
</div>
