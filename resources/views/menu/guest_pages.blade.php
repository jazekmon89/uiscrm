@section('navbar')
	@parent
@if (\Request::is('login'))
	<li>{!! link_to_route('register-front', "Register") !!}</li>
@elseif (\Request::is('login'))
	<li>{!! link_to_route('login', "Login") !!}</li>
@elseif (Auth::guest())
	<li>{!! link_to_route('login', "Login") !!}</li>
	<li>{!! link_to_route('register-front', "Register") !!}</li>
@endif
	<li>{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
@endsection