<ul class="sidebar-nav grid-100">
	<li class="{{ Route::currentRouteNamed('rfqs.view') ? 'active' : '' }}"><a href="{{ route('rfqs.view', $RFQID) }}">View Current RFQ</a></li>
	<li class="{{ Route::currentRouteNamed('rfqs.tasks') ? 'active' : '' }}"><a href="{{ route('rfqs.tasks', $RFQID) }}">Tasks</a></li>
	<li class="{{ Route::currentRouteNamed('rfqs.notes') ? 'active' : '' }}"><a href="{{ route('rfqs.notes', $RFQID) }}">Notes</a></li>
	<li class="{{ Route::currentRouteNamed('rfqs.attachments') ? 'active' : '' }}"><a href="{{ route('rfqs.attachments', $RFQID) }}">Attachments</a></li>
	<li class="{{ Route::currentRouteNamed('rfqs.versions') ? 'active' : '' }}"><a href="{{ route('rfqs.versions', $RFQID) }}">Versions</a></li>
</ul>