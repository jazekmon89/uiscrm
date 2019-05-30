@inject("FormHelper", "App\Helpers\FormGroupMapHelper")
@foreach($children as $child)
	@php $isLinked = $FormHelper->isLinkedToOthers($child->Name); @endphp
	<div 
		class="sub-group {{ $isLinked ? 'isLinked hidden' : '' }}" 
		id="sub-group-{{ $child->Name }}"
		@if($isLinked)
			data-linked="{{ json_encode($FormHelper->getGroupLinkedTo($child->Name)) }}"
		@endif
		data-formpath="{{ $baseKey }}"
		>
		<h4 class="grid-100">{{$child->DisplayText}}</h4>
		<div class="grid-100">
		@include('Quotes.Form.DynamicQuestions-Groups', ['group' => $child, 'baseKey' => $baseKey])	
		</div>
	</div>
@endforeach