<ul class="bem-sidebar__nav-inner grid-100" role="menu">
	<li class="list-group-item"><a href="{{ route('insurancequotes.index') }}" class="normal-link"><u>Back to all Quotes</u></a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('insurancequotes.view') ? 'active' : '' }}"><a href="{{ route('insurancequotes.view', $QuoteID) }}">Compare Quotes</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('insurancequotes.tasks') ? 'active' : '' }}"><a href="{{ route('insurancequotes.tasks', $QuoteID) }}">Tasks</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('insurancequotes.notes') ? 'active' : '' }}"><a href="{{ route('insurancequotes.notes', $QuoteID) }}">Notes</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('insurancequotes.attachments') ? 'active' : '' }}"><a href="{{ route('insurancequotes.attachments', $QuoteID) }}">Attachments</a></li>
</ul>


