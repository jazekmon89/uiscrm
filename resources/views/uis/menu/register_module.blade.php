@section('navbar')
	@parent
	
	@if (Auth::guest())
	<li>{!! link_to_route('login', "Login") !!}</li>
	@else
	 <li class="dropdown">
	    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
	        {{Auth::user()->name}}<span class="caret"></span>
	    </a>

	    <ul class="dropdown-menu" role="menu">
	        <li>
	        	<li class="list-group-item">
	        		{!! link_to_route('logout', "Logout") !!}
	        	</li>

	        </li>
	    </ul>
	</li>
	@endif
	<li>{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
@endsection