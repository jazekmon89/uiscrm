<div class="form">
	@if($current->Name === 'InsuranceHistory')
		<p><h5>In the last 5 years</h5></p>
	@endif
	@if(app('request')->PolicyTypeID === 'A6591919-4E04-4C92-BF0E-E0F69166EAA0' && $current->Name === 'InsuranceOptions')
		@include('Quotes.Form.WorkersComp-InsuranceOptions', ['group' => $current, 'baseKey' => ''])
	@else
		@include('Quotes.Form.DynamicQuestions-Groups', ['group' => $current, 'baseKey' => ''])	
	@endif
	@if(isset($current->partials) && $current->partials)
		<div class="partials">
			@foreach($current->partials as $partial)
				@include($partial, compact('current'))
			@endforeach
		</div>
	@endif
</div>