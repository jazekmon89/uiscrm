@php
	$active = isset($active) ? $active : "";
@endphp
<div class="bem-sidebar__list-group-container">
	@if(!$document->isEmptyGroupBlock('gi-rfqs-submenu'))
		@dynamicblock('gi-rfqs-submenu')
	@endif
</div>