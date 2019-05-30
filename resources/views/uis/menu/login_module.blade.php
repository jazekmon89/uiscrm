@section('navbar')
	@parent
	
	@if (Auth::guest())
	<li class="list-group-item">{!! link_to_route('login', "Login") !!}</li>
	<li class="list-group-item">{!! link_to_route('register-front', "New User") !!}</li>
	@endif
	<li class="list-group-item">{!! link_to_route('inquiries.create', "Inquiry") !!}</li>
	@if (Auth::id())
	 <li class="list-group-item"><!--class="dropdown">-->
	    <!--a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"-->
	    <!--a href="#" role="button" aria-expanded="false">
	        {{Auth::user()->name}}<span class="caret"></span>
	    </a>-->

	    <!--ul class="dropdown-menu" role="menu">
        	<li class="list-group-item">-->
        		{{ link_to_route('logout', "Logout") }}
        	<!--</li>
	    </ul>-->
	</li>
	@endif
@endsection