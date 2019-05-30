<!-- <ul class="bem-sidebar__nav-inner grid-100" role="menu"> -->
	<li class="list-group-item {{ Route::currentRouteNamed('client.profiles') ? 'active' : '' }}"><a href="{{ route('client.profiles', $ClientID) }}">Current Client</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('client.recommendations') ? 'active' : '' }}"><a href="{{ route('client.recommendations', $ClientID) }}">Recommendations</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('client.show-contacts') ? 'active' : '' }}"><a href="{{ route('client.show-contacts', $ClientID) }}">Contacts</a></li>
<!-- </ul> -->