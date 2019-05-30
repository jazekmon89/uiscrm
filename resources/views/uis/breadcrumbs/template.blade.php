@if ($breadcrumbs)
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->first)
            	<li><a href="{{ $breadcrumb->url }}"><small><i class="fa fa-dashboard"></i> {{ $breadcrumb->title }}</small></a></li>
            @elseif (!$breadcrumb->last)
                <li><a href="{{ $breadcrumb->url }}"><small>{{ $breadcrumb->title }}</small></a></li>
            @else
                <li class="active"><small>{{ $breadcrumb->title }}</small></li>
            @endif
        @endforeach
    </ol>
@endif