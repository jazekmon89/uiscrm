@inject('FormHelper', 'App\Helpers\FormGroupMapHelper')
@inject('RFQRequest', 'App\Http\Requests\Quotes\RFQRequest')
@php
	$publicProductLiability_flag = false;
	$publicProductLiability_key = null;
	foreach($current->questions as $key=>$question){
		if($question->Name == 'PublicProductLiability' && $question->DisplayOrder == '350'){
			$publicProductLiability_flag = true;
			if($publicProductLiability_flag)
				$publicProductLiability_key = $key;
		}
	}
	if($publicProductLiability_flag){
		$temp = $current->questions[$publicProductLiability_key];
		$new_arrangement = [];
		unset($current->questions[$publicProductLiability_key]);
		$current->questions = array_values($current->questions);
		$to_arrange_flag = false;
		foreach($current->questions as $key => $question){
			if(strpos($question->Name,'PublicProductsLiability') !== false){
				$to_arrange_flag = true;
				$new_arrangement[$key+1] = $temp;
			}elseif($to_arrange_flag)
				$new_arrangement[] = $question;
		}
		$current->questions = array_replace($current->questions, $new_arrangement);
	}
@endphp
@foreach($current->questions as $question)
	@php  
		$cls = [ $question->FormQuestionTypeName, $question->Name ] ;
		$cls[] = $question->IsMandatory === 'Y' ? 'required' : '';

		if ($isLinked = $FormHelper->isLinkedToOthers($question->FormQuestionID))
			$cls[] =  'isLinked hidden';
		$cls[] = 'disp-order-' . ($question->DisplayOrder ?: '0');
		if(in_array($question->Name,['FullTimeStaffCount','PartTimeStaffCount']))
			$cls[] = 'row col-md-6';
		else
			$cls[] = 'row';
		if($question->Name == 'FullTimeStaffCount')
			$cls[] = 'p-0 m-right-15';
		elseif($question->Name == 'PartTimeStaffCount')
			$cls[] = 'p-0 m-left-15';
		$label = $question->DisplayText;

		
		$column_width_class = '';
		if($question->FormQuestionTypeName){
			if(in_array($question->Name,['FullTimeStaffCount','PartTimeStaffCount']))
				$column_width_class = 'col-md-5';
			else if(in_array($question->Name,['Number','PortableValuableItem_BookValue']) || in_array($question->Name,['Address','PortableValuableItem_Description']))
				$column_width_class = 'col-md-3';
			else if(in_array($question->FormQuestionTypeName,['Boolean']))
				$column_width_class = 'col-md-7';
			else if(in_array($question->FormQuestionTypeName, ['Text','Number','SelectOne','Date']))
				$column_width_class = 'col-md-3';
			else if(in_array($question->FormQuestionTypeName,['SelectMulti']))	
				$column_width_class = 'col-md-7';						
			else $column_width_class = 'col-md-9';
			/*else if(in_array($question->Name,['BusinessBaseLocation','BusinessOperationAge']))
				$column_width_class = 'col-md-3';
			else $column_width_class = 'col-md-9';*/
		}else if(!in_array('col-md-6', $cls))
			$column_width_class = 'col-md-12';

		$label_column_width_class = '';
		if($question->FormQuestionTypeName){
			if(in_array($question->Name,['FullTimeStaffCount','PartTimeStaffCount']))
				$label_column_width_class = 'col-md-6 m-right-8';
			elseif($question->Name == 'Address' || $current->Name == 'SituationAtRisk')
				$label_column_width_class = 'col-md-3';
			else if(in_array($question->Name,['Number','PortableValuableItem_BookValue']) || in_array($question->Name,['Address','PortableValuableItem_Description']))
				$label_column_width_class = 'col-md-2';	
			else if(in_array($question->FormQuestionTypeName, ['Text','Number','SelectOne','Date','Boolean','SelectMulti']))
				$label_column_width_class = 'col-md-5';			
			else if(in_array($question->FormQuestionTypeName,['Date']))
				$label_column_width_class = 'col-md-6';
			else $label_column_width_class = 'col-md-3';
		}else if(!in_array('col-md-6', $cls))
			$label_column_width_class = 'col-md-3';
	@endphp
	<div class="{{ implode(' ', $cls) }}" 
		@if($isLinked) 
			data-linked="{{ json_encode($FormHelper->getQuestionLinkedTo($question->FormQuestionID)) }}"
		@endif 
			data-formpath="{{ $baseKey }}"
			id="qid-{{ $question->FormQuestionID }}"
		>
		@if(!($question->Name == 'BusinessType' && trim(strtolower($disptext)) == 'mobile coffee van/trailer' && (!Auth::user() || (Auth::user() && !Auth::user()->is_adviser))))
		<div class="{{ $label_column_width_class }} question-disp">
			
			{{ Form::jLabel("{$baseKey}.$question->FormQuestionID",$label, [], false) }}
			{{-- @if($question->Comments) --}}
				<!-- <i class="label label-default">{{$question->Comments}}</i> -->
			{{-- @endif --}}
			
		</div>
		@endif
		<div class="form-group {{ $question->FormQuestionTypeName == 'Address'?'col-md-9':$column_width_class }}">
			@if($question->FormQuestionTypeName == 'Address')
				@php $use_addr = $RFQRequest->hasBusinessFields() ? 'mail_addr' : 'home_addr'; @endphp
				<div class="row">
					<div class="{{ in_array($question->Name,['FullTimeStaffCount','PartTimeStaffCount'])?$label_column_width_class:'col-md-12' }}">
						<div class="grid-100 disp-block">
							
							<div class="grid-100 form-inline u-m-a-label">
								<label class="inline-label">Is the business address the same as {{ $use_addr == 'mail_addr' ? 'mailing' : 'home' }} address ?</label>
								{{ Form::jBoolean("{$baseKey}.{$question->FormQuestionID}.use_address", [$use_addr => "Yes", "" => "No"], 0, ['class' => 'u-m-a', 'data-address-use' => $use_addr, 'before' => '<div class=\'disp-inline-block\'>']) }}

								
							</div>

						</div>
					</div>

					<div class="address-wrapper hidden">

						<p class="col-md-12">(Cannot be a PO Box)</p>
						@include("Quotes.Form.partials.address-form", ['baseKey' => "{$baseKey}.{$question->FormQuestionID}"])
					</div>
				</div>
			@elseif($question->FormQuestionTypeName == 'Text')
				{{ Form::jInput("text", "{$baseKey}.$question->FormQuestionID", null, ['class' => 'form-control'])}}

			@elseif($question->FormQuestionTypeName == 'Textarea')
				{{ Form::jInput("textarea", "{$baseKey}.$question->FormQuestionID", null, ['class' => 'form-control'])}}

			@elseif($question->FormQuestionTypeName == 'Number')
				@if($question->Name == 'PortableValuableItem_BookValue')
				<span class='dollar-currency'>$</span>	
				@endif
				{{ Form::jInput("number", "{$baseKey}.$question->FormQuestionID", null, ['class' => 'form-control'])}}

			@elseif($question->FormQuestionTypeName =='SelectOne')
				@php

					$choices = arr_pairs($question->choices, 'FormQuestionPossChoiceID', 'DisplayText');
			
					if(app('request')->PolicyTypeID === "C87EEB92-8B1E-4C95-9ACE-E66A42E3B8BB"
					&& $question->Name === 'BusinessType')
					{
						array_pull($choices, "14946461-C2A1-E611-902E-000C292D0644");
						array_pull($choices, "15946461-C2A1-E611-902E-000C292D0644");
					}
				@endphp
				@if($question->Name == 'BusinessType' && trim(strtolower($disptext)) == 'mobile coffee van/trailer' && (!Auth::user() || (Auth::user() && !Auth::user()->is_adviser)))
					@php 
						$businessType = '';
						foreach($choices as $k=>$i){
							if(trim(strtolower($i)) == 'mobile only')
								$businessType = $k;
						}
					@endphp
					{{ Form::jInput("hidden", "{$baseKey}.$question->FormQuestionID", $businessType)}}
				@else
					{{ Form::jInput("select", "{$baseKey}.$question->FormQuestionID", ["" => "Please select"] + $choices, null, ['class' => 'form-control col-md-3' ])}}
				@endif

			@elseif($question->FormQuestionTypeName =='SelectMulti')
				{{ Form::selectMulti("{$baseKey}.$question->FormQuestionID", arr_pairs($question->choices, 'FormQuestionPossChoiceID', 'DisplayText')) }}

			@elseif($question->FormQuestionTypeName == 'Boolean')
				<div class="row">
					<div class="col-md-12">
						<!--br/-->
						{{Form::jBoolean("{$baseKey}.$question->FormQuestionID", ['Y' => 'Yes', 'N' => 'No'])}}
					</div>
				</div>
			@elseif($question->FormQuestionTypeName == 'Date')
				@if(preg_match("#(birthdate|birth)#i", $question->Name))
					<div class="input-group datetimepicker">
						{{Form::jInput("datetime", "{$baseKey}.$question->FormQuestionID", null, ["class" => 'form-control'])}}
				@else
					<div class="input-group datetimepicker" data-date-format="D/M/YYYY H:i:s">
					{{Form::jInput("datetime", "{$baseKey}.$question->FormQuestionID", null, ["class" => 'form-control'])}}
				@endif
					<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
				</div>
			@endif			
		</div>
	</div>
@endforeach
