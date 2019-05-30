@php 
	$baseKey .= ($baseKey ? '.' : '') . $group->Name;
	$baseID = app('FormMacros')->transformKey($baseKey);
	$group_name = snake_case($group->Name);
@endphp

@if ($group->IsRepeating === 'Y')

	@php 
		$repeat = ["repeat" => ".questions", "container" => "#grp-{$group_name}", "text" => "+ {$group->DisplayText}"];
		$items = Form::getInputValue($baseKey, [null]);
		ksort($items);

		//include('Quotes.Form.partials.question-repeat-buttons', $repeat)
	@endphp

	

	<div class="panel-group grid-100" id='grp-{{$group_name}}'>
		@php $cnt = 1 @endphp
		@foreach($items as $i => $val)
			<div class='panel panel-default questions repeatable {{$i % 2 === 0 ? "odd" : "even"}}' 
				data-repeat-match='{{$baseKey}}'
				data-repeat-title=".panel-title a"
				data-repeat-index={{$i}}
				id="{{$baseID}}-{{$i}}-x"
				>
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href='#{{$baseID}}-{{$i}}-toggle'>{{$group->DisplayText . " #" . ($cnt)}}</a>
						<button data-remove='#{{$baseID}}-{{$i}}-x' class="pull-right"><i class="fa fa-trash-o"></i></button>
					</h4>
				</div>
				<div class="panel-collapse collapse in" id="{{$baseID}}-{{$i}}-toggle" data-toggle-area="collapse">
					<div class="panel-body">
						<div class="spacer">&nbsp;</div>
						@include('Quotes.Form.DynamicQuestions-Questions', ['current' => $group, 'baseKey' => $baseKey . ".$i"])
						<div class="sub-group">
							@if(isset($group->children) && $group->children)
								
								@include("Quotes.Form.partials.subgroups", ['children' => $group->children, 'baseKey' => $baseKey . ".$i"])
							@endif
						</div>	
					</div>
					<!-- <div class="panel-footer"></div> -->
				</div>
			</div>
		@php $cnt++ @endphp
		@endforeach

		<div class="panel-group-bottom disp-inline-block grid-100 text-right v-space-5x">
			@include('Quotes.Form.partials.question-repeat-buttons', $repeat)
		</div>
	</div>
@else	
	<div class='questions' id='grp-{{$group_name}}'>
		@include('Quotes.Form.DynamicQuestions-Questions', ['current' => $group, 'baseKey' => $baseKey])
		
			@if(isset($group->children) && $group->children)
				<div class="sub-groups" >
					@if(isset($group->children) && $group->children)
						@include("Quotes.Form.partials.subgroups", ['children' => $group->children, 'baseKey' => $baseKey])	
					@endif
				</div>	
			@endif
			
	</div>
@endif