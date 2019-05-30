@php
	$active = isset($active) ? $active : "";
@endphp
<div class="bem-sidebar__list-group-container">      
  	<ul class="bem-sidebar__nav-inner grid-100" role="menu">  
  		<li class="list-group-item"><a href="{{ route('client.profiles') }}" class="normal-link"><u>Back to all Clients</u></a></li>			
		@if(!$document->isEmptyGroupBlock('gi-client-profile-submenu'))
			@dynamicblock('gi-client-profile-submenu')
		@endif		
		<!-- <li class="list-group-item {{ $active == 'rfqs' ? 'active' : '' }}">
			<a href="{{ route('rfqs.index') }}">RFQ's</a>
			@if(!$document->isEmptyGroupBlock('gi-rfqs-submenu'))
				@dynamicblock('gi-rfqs-submenu')
			@endif
		</li>
		<li class="list-group-item">
			<a href="#">Policies</a>
			@if(!$document->isEmptyGroupBlock('gi-policies-submenu'))
				@dynamicblock('gi-client-profile-submenu')
			@endif
		</li>
		<li class="list-group-item"><a href="{{ route('insurancequotes.index') }}">Quotes</a></li>
		<li class="list-group-item"><a href="#">Claims</a></li> -->
	</ul>
</div>