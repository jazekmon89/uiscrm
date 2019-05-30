@jsblock("Quotes.Form.partials.covers-input-handlers", "covers-handler")
@if (!empty($Covers['Sets']))
	<h3 class="col-md-12">Insurance coverage</h3>
		@foreach($Covers['Sets'] as $Cover)
			@push('cover-set-levels')
				<div class="cover-set-level hidden" id="cover-set-level-{{$Cover->CoverLevelSetID}}">
					<h4><b class="cover-set">{{$Cover->DisplayText}}</b> (Automatically covered for the following options)</h4>
					<table class="table col-md-12">
						<thead>
							<tr>
								<th class="col-md-4">Insured for</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Cover->levels as $level)
							<tr>
								<td>{{$level->CoverDisplayText}}</td>
								<td>{{$level->CoverLevelDisplayText}}</td>									
							</tr>
							@endforeach
						</tbody>
					</table>	

				</div>
			@endpush
		@endforeach
		<div class="cover-set required" style="display:inline-block;">
			<div class="col-md-4">
				{{Form::jLabel('Covers.Set', "Total fit out/contents/stocks sum insured \r (Cost of replacement in today's value)")}}
			</div>
			<div class="col-md-8">	
				{{Form::jInput('select', 'Covers.Set', arr_pairs($Covers['Sets'], 'CoverLevelSetID', 'DisplayText'))}}
			</div>
			
			<div class="cover-set-levels col-md-12">	
				@stack('cover-set-levels')
			</div>	
		</div>
@endif
@if (!empty($Covers['Covers']))
	<div class="covers-covers">
		@foreach($Covers['Covers'] as $Cover)
		@php 
			$required = $Cover->IsMandatory === 'Y';
			$recommended = $Cover->IsDefaultRecommendation === 'Y';
			$stackKey = 'covers-cover-' . ($required || $recommended ? 'displayed' : 'hidden');
		@endphp
		@push($stackKey)
		<div class="covers-cover row  {{$required ? 'required' : ''}} {{!$required && !$recommended ? 'hidden' : '' }}">
			<div class="col-md-12 form-group">
				{{Form::jLabel("Covers.Cover.{$Cover->CoverID}", $Cover->displayText, ['class' => 'col-md-4'])}}
				@if($Cover->levels) 	
					{{Form::jInput("select", "Covers.Cover.{$Cover->CoverID}", arr_pairs($Cover->levels, "CoverLevelID", "DisplayText"))}}
				@else
					{{Form::jInput("checkbox", "Covers.Cover.{$Cover->CoverID}", 1, $Cover->IsMandatory === 'Y')}}
				@endif
			</div>
		</div>
		@endpush	
		@endforeach
		<div class='covers-displayed'>
			@stack('covers-cover-displayed')
		</div>
		<div class='covers-hidden'>
			<div class='col-md-12 hidden-trigger form-group'>
				{{Form::jInput("checkbox", "Covers.Cover.optional-trigger", "1", false)}}
				{{Form::jLabel("Covers.Cover.optional-trigger", "Click here to see optional covers")}}
				<div class="border"></div>
			</div>
			
			@stack('covers-cover-hidden')			
			
		</div>
	</div>
@endif
@if (empty($Covers))
	{{Form::jInput("hidden", "Covers.skip", 1)}}
@endif
