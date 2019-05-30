@inject("Policy", "App\Helpers\PolicyHelper")
@jsblock('Quotes.Form.partials.claims-input-handlers', 'claims-handlers')
@php 
	$repeat = ["repeat" => ".claims-list", "container" => "#claim-list-container", "id" => "claims-repeater",  "text" => "+ claims"];
	$has_claims = (bool)Form::getInputValue("Claims.Claims");
	$has_others = (bool)Form::getInputValue("Claims.OtherClaims");
	$_policies = arr_pairs($Policy->getTypes(), "PolicyTypeID", "DisplayText");
	//$_claim_type = [""=>"Select"];
	$_claim_type = null;
	foreach($_policies as $k=>$i){
		if($i == $disptext)
			$_claim_type = $k;
		//$_claim_type[$k] = $i;
	}
	//$_claim_type = [""=>"Select"] + (count($_claim_type)?$_claim_type:$_policies);
@endphp
	<h5 class="bem-text_left">Claim History</h5>
	<div class="row">
		{{-- Motor Vehicle --}}
		@if(app('request')->PolicyTypeID === '8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F')
			<p class="col-md-5 question-disp"><strong>In the last 5 years have you made any motor vehicle claims ?</strong><</p>
		@else
			<p class="col-md-5 question-disp"><strong>In the last 5 years have you suffered any losses or otherwise or has any claims made against you ?</strong></p>
		@endif
		<div class="form-group col-md-7">
			{{ Form::jBoolean("Claims.trigger.claims", ['Y' => 'Yes', 'N' => 'No'], $has_claims ? 'Y' : 'N', ['class' => 'claims-trigger', 'data-target' => '#claims-claims'])}}
		</div>
	</div>


	<div class="claims-container grid-100 hidden" id="claims-claims">
		<table class="table">
			<thead>
				<tr>
					@if(false)
					<th class="col-md-2" style="width: 5%;">@include('Quotes.Form.partials.question-repeat-buttons', $repeat)</th>
					<th class="col-md-3" style="width: 18%">Insurer</th>
					<th class="col-md-4" style="width: 25%" colspan='2'>Insurance Period <br/>(Begin - End)</th>
					<th class="col-md-2 " style="width: 13%;{{ app('request')->PolicyTypeID === '8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F' ? 'display:none;' : '' }}">Type of Claim</th>
					<th class="col-md-2" style="width: 13%">Amount Paid</th>
					<th class="col-md-2" style="width: 13%">At Fault</th>
					<th class="col-md-2" style="width: 13%">Finalized</th>
					@endif
					<th class="col-md-2" style="width: 5%;">@include('Quotes.Form.partials.question-repeat-buttons', $repeat)</th>
					<th class="col-md-3" style="width: 21%">Insurer</th>
					<th class="col-md-4" style="width: 13%">Accident date</th>
					<th class="col-md-2 hidden" style="width: 13%;">Type of Claim</th>
					<th class="col-md-2" style="width: 13%">Amount Paid</th>
					<th class="col-md-2 {{$disptext == 'Workers Compensation'?'hidden':''}}" style="width: 13%">At Fault</th>
					<th class="col-md-2" style="width: 13%">Finalized</th>
					<th class="col-md-2" style="width: {{$disptext == 'Workers Compensation'?35:22}}%">Description</th>
				</tr>
			</thead>
			<tbody id="claim-list-container">
				@foreach(Form::getInputValue("Claims.Claims", [null]) as $i => $val)
				<tr 
					class="claims-list"
					data-repeat-match='Claims.Claims'
					data-repeat-index={{$i}}
					id="Claims-Claims-{{$i}}-x"
					>
					<td class="text-left">
						<button data-remove='#Claims-Claims-{{$i}}-x' class=""><i class="fa fa-trash-o"></i></button>
					</td>
					<td class="form-group">
						{{ Form::jInput("text", "Claims.Claims.{$i}.InsurerCompanyName", null, ['class' => 'form-control']) }}
					</td>
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.Claims.{$i}.InsuranceAccidentDate", null, ['placeholder' => 'Date', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					@if(false)
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.Claims.{$i}.InsurancePeriodBeginDate", null, ['placeholder' => 'From', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.Claims.{$i}.InsurancePeriodEndDate", null, ['placeholder' => 'To', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					@endif
					@if(false)
					<td class="form-group" style="{{ app('request')->PolicyTypeID === '8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F' ? 'display:none;' : '' }}">
						@if(app('request')->PolicyTypeID === '8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F')
			            	{{ Form::jInput("hidden", "Claims.Claims.{$i}.TypeOfClaim", "8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F") }}
			            @elseif(false && $disptext == 'Workers Compensation')
			            	{{ Form::jInput("select", "Claims.Claims.{$i}.TypeOfClaim", $_claim_type, null, ['class' => 'form-control']) }}
			            @else
			            	{{ Form::jInput("select", "Claims.Claims.{$i}.TypeOfClaim", $_claim_type, null, ['class' => 'form-control']) }}
			            @endif
					</td>
					@endif
					<td class="form-group hidden">
						{{ Form::jInput("hidden", "Claims.Claims.{$i}.TypeOfClaim", $_claim_type) }}
					</td>
					<td class="iamnt-paid form-group">
						{{ Form::jInput("number", "Claims.Claims.{$i}.AmountPaid", null, ['class' => 'form-control']) }}
					</td>
					@if(false)
					<td class="form-group text-left">
						{{ Form::jInput("checkbox", "Claims.Claims.{$i}.AtFault", 'Y', false, ['class' => 'form-control', 'style' => 'height:20px']) }}
					</td>
					<td class="form-group text-left">
						{{ Form::jInput("checkbox", "Claims.Claims.{$i}.Finalized", 'Y', false, ['class' => 'form-control', 'style' => 'height:20px']) }}
					</td>
					@endif
					@if($disptext == 'Workers Compensation')
					<td class="form-group hidden">
						{{ Form::jInput("hidden", "Claims.Claims.{$i}.AtFault", '', ['class' => 'form-control']) }}
					</td>
					@else
					<td class="form-group text-left">
						{{ Form::jInput("select", "Claims.Claims.{$i}.AtFault", [''=>'Select','N'=>'Not at fault', 'Y'=>'At fault'], null, ['class' => 'form-control']) }}
					</td>
					@endif
					<td class="form-group">
						{{ Form::jInput("select", "Claims.Claims.{$i}.Finalized", [''=>'Select','N'=>'Not Finalized', 'Y'=>'Finalized'], null, ['class' => 'form-control']) }}
					</td>
					<td class="form-group">
						{{ Form::jInput("textarea", "Claims.Claims.{$i}.InsuranceDescription", null, ['placeholder'=>'Describe what happened.','class' => 'form-control', 'rows'=>'3']) }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>	

	@if(app('request')->PolicyTypeID === '8F61A25E-1B5E-460A-969D-1BE8B0ACAB3F')
		<div class="row">
			<p class="col-md-5 question-disp"><strong>In the last 5 years have you hade any other claims related to the covers selected i.e. Machinery Breakdown, Business Interruption, Public Liability, etc.?</strong></p>

			<div class="form-group col-md-7">
				{{ Form::jBoolean("Claims.trigger.others", ['Y' => 'Yes', 'N' => 'No'], $has_others ? 'Y' : 'N', ['class' => 'claims-trigger', 'data-target' => '#claims-others'])}}
			</div>
		</div>
		@php
			$repeat['container'] = '#claim-others-container';
		@endphp
		<div class="claims-container grid-100 hidden" id="claims-others">
			<table class="table">
			<thead>
				<tr>
					@if(false)
					<th class="col-md-2" style="width: 5%;">@include('Quotes.Form.partials.question-repeat-buttons', $repeat)</th>
					<th class="col-md-3" style="width: 18%">Insurer</th>
					<th class="col-md-4" style="width: 25%" colspan='2'>Insurance Period <br/>(Begin - End)</th>
					<th class="col-md-2" style="width: 13%">Type of Claim</th>
					<th class="col-md-2" style="width: 13%">Amount Paid</th>
					<th class="col-md-2" style="width: 13%">At Fault</th>
					<th class="col-md-2" style="width: 13%">Finalized</th>
					@endif
					<th class="col-md-2" style="width: 5%;">@include('Quotes.Form.partials.question-repeat-buttons', $repeat)</th>
					<th class="col-md-3" style="width: 13%">Insurer</th>
					<th class="col-md-4" style="width: 13%">Accident date</th>
					<th class="col-md-2" style="width: 10%">Type of Claim</th>
					<th class="col-md-2" style="width: 13%">Amount Paid</th>
					<th class="col-md-2 {{$disptext == 'Workers Compensation'?'hidden':''}}" style="width: 10%">At Fault</th>
					<th class="col-md-2" style="width: 10%">Finalized</th>
					<th class="col-md-2" style="width: {{$disptext == 'Workers Compensation'?26:16}}%">Description</th>
				</tr>
			</thead>
			<tbody id="claim-others-container">
				@foreach(Form::getInputValue("Claims.OtherClaims", [null]) as $i => $val)
				<tr 
					class="claims-list"
					data-repeat-match='Claims.OtherClaims'
					data-repeat-index={{$i}}
					id="Claims-OtherClaims-{{$i}}-x"
					>
					<td class="text-left">
						<button data-remove='#Claims-OtherClaims-{{$i}}-x' class=""><i class="fa fa-trash-o"></i></button>
					</td>
					<td class="form-group">
						{{ Form::jInput("text", "Claims.OtherClaims.{$i}.InsurerCompanyName", null, ['class' => 'form-control']) }}
					</td>
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.OtherClaims.{$i}.InsuranceAccidentDate", null, ['placeholder' => 'Date', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					@if(false)
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.OtherClaims.{$i}.InsurancePeriodBeginDate", null, ['placeholder' => 'From', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					<td class="form-group">
						{{ Form::jInput("datetime", "Claims.OtherClaims.{$i}.InsurancePeriodEndDate", null, ['placeholder' => 'To', 'class' => 'datetimepicker form-control', 'data-date-format' => 'M/YYYY']) }}
					</td>
					@endif
					@if(false)
					<td class="form-group">
						{{ Form::jInput("text", "Claims.OtherClaims.{$i}.TypeOfClaim", null, ['class' => 'form-control']) }}
					</td>
					@endif
					<td class="form-group">
						{{ Form::jInput("select", "Claims.OtherClaims.{$i}.TypeOfClaim", [''=>'Select','Fire and perils'=>'Fire and perils', 'Storm damage'=>'Storm damage', 'Theft/Burglary'=>'Theft/Burglary', 'Glass'=>'Glass', 'Money'=>'Money', 'Machinery breakdown'=>'Machinery breakdown', 'Loss of income'=>'Loss of income', 'Public liability'=>'Public liability', 'Product liability'=>'Product liability'], null, ['class' => 'form-control']) }}
					</td>
					@if(false)
					<td class="form-group hidden">
						{{ Form::jInput("hidden", "Claims.OtherClaims.{$i}.TypeOfClaim", $_claim_type) }}
					</td>
					@endif
					<td class="iamnt-paid form-group">
						{{ Form::jInput("number", "Claims.OtherClaims.{$i}.AmountPaid", null, ['class' => 'form-control']) }}
					</td>
					@if(false)
					<td class="form-group text-left">
						{{ Form::jInput("checkbox", "Claims.OtherClaims.{$i}.AtFault", 'Y', false, ['class' => 'form-control', 'style' => 'height:20px']) }}
					</td>
					<td class="form-group text-left">
						{{ Form::jInput("checkbox", "Claims.OtherClaims.{$i}.Finalized", 'Y', false, ['class' => 'form-control', 'style' => 'height:20px']) }}
					</td>
					@endif
					@if($disptext == 'Workers Compensation')
					<td class="form-group hidden">
						{{ Form::jInput("hidden", "Claims.OtherClaims.{$i}.AtFault", '', ['class' => 'form-control']) }}
					</td>
					@else
					<td class="form-group text-left">
						{{ Form::jInput("select", "Claims.OtherClaims.{$i}.AtFault", [''=>'Select', 'N'=>'Not at fault', 'Y'=>'At fault'], null, ['class' => 'form-control']) }}
					</td>
					@endif
					<td class="form-group text-left">
						{{ Form::jInput("select", "Claims.OtherClaims.{$i}.Finalized", [''=>'Select', 'N'=>'Not Finalized', 'Y'=>'Finalized'], null, ['class' => 'form-control']) }}
					</td>
					<td class="form-group text-left">
						{{ Form::jInput("textarea", "Claims.OtherClaims.{$i}.InsuranceDescription", null, ['placeholder'=>'Describe what happened.','class' => 'form-control', 'rows'=>'3']) }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		</div>
	@endif


