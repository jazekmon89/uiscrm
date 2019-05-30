<ul class="sidebar-nav grid-100">
	<li class="{{ Route::currentRouteNamed('client.profiles') ? 'active' : '' }}">
		<a href="{{ route('client.profiles', $ClientID) }}">View Current Client</a>
	</li>
	<li class="{{ Route::currentRouteNamed('client.recommendations') ? 'active' : '' }}">
		<a href="{{ route('client.recommendations', $ClientID) }}">Recommendations</a>
	</li>
	<li class="{{ Route::currentRouteNamed('client.show-contacts') ? 'active' : '' }}">
		<a href="{{ route('client.show-contacts', $ClientID) }}">Contacts</a>
	</li>
</ul>