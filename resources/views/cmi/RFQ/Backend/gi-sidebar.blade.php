<ul class="bem-sidebar__nav-inner grid-100" role="menu">
	<li class="list-group-item"><a href="{{ route('rfqs.index') }}" class="normal-link"><u>Back to all RFQs</u></a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('rfqs.view') ? 'active' : '' }}"><a href="{{ route('rfqs.view', $RFQID) }}">RFQ Details</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('rfqs.versions') ? 'active' : '' }}"><a href="{{ route('rfqs.versions', $RFQID) }}">Versions</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('rfqs.tasks') ? 'active' : '' }}"><a href="{{ route('rfqs.tasks', $RFQID) }}">Tasks</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('rfqs.notes') ? 'active' : '' }}"><a href="{{ route('rfqs.notes', $RFQID) }}">Notes</a></li>
	<li class="list-group-item {{ Route::currentRouteNamed('rfqs.attachments') ? 'active' : '' }}"><a href="{{ route('rfqs.attachments', $RFQID) }}">Attachments</a></li>
</ul>