@php
	$active = isset($active) ? $active : "";
@endphp
<div class="bem-sidebar__list-group-container">
	@if(!$document->isEmptyGroupBlock('gi-quotes-submenu'))
		@dynamicblock('gi-quotes-submenu')
	@endif
</div>