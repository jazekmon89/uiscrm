@php $theme = Auth::check() && Auth::user()->is_client ? 'jci' : 'cmi' @endphp
<!DOCTYPE html>
<html lang="en" class="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @title()</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ URL::to('_cmi-favicon.ico') }}">
  
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Cabin|Oxygen" rel="stylesheet">

    <!-- css files -->
    {{-- css files only! --}}    
    @assets('css')

    <link href="/css/_{{ $theme }}/_layout.css" rel="stylesheet"> 
    <link href="/css/_{{ $theme }}/_media.css" rel="stylesheet"> 
    <link href="/css/_{{ $theme }}/_utils.css" rel="stylesheet"> 

    <!-- css blocks -->
    {{-- css blocks only! --}}
    @assets('cssblock')

    @if(isset($css) && count($css))
        @foreach($css as $i)
            <style id="{{ $i['1'] }}">
            @includeif($i['0'], (array_key_exists('2', $i)?$i['2']:[]))
            </style>
        @endforeach
    @endif

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]); !!}
    </script> 
</head>
<body class="@body_class() {{ !Auth::check() ? 'guest' : (Auth::user()->is_adviser ? 'adviser' : 'client') }} background-half">   
    <header id="header">
        <div class="upper">            
            <div class="top-inquiry-header" id="top-header">
                <div id="logo">   
                    <a href="{{ url("/") }}">
                        <img src="{{ url("/images/logo-{$theme}.jpg") }}">
                    </a>
                </div>                    
            </div>          
        </div>              
    </header>
    <div id="content" class="container">
        @yield('body')
    </div>

    @assets('js')
    @assets('jsblock')
    
    @if(isset($js) && count($js))
        @foreach($js as $i)
            <script type="text/javascript" id="{{ $i['1'] }}"{!! array_key_exists('3', $i)?' src="'.$i['3'].'"':'' !!}>
            @if(!empty($i[0]))
                @includeif($i['0'], (array_key_exists('2', $i)?$i['2']:[]))
            @endif
            </script>
        @endforeach
    @endif

    <script>
        $(document).ready(function() {
            (adjust = function() {
                var w = $(window),
                b = $('body'),
                h = $('#header'),
                c = $('#content'),
                f = $('#footer')
                v = w.outerHeight();
                
                if (b.outerHeight() < v) {
                    c.height(v - (h.outerHeight() + 50 + f.outerHeight()));
                }
                else {
                    c.height("auto");
                }
                
            })();

            $(window).resize(function() {
                setTimeout(adjust, 100);
            });

            $('#main-menu .navbar-brand').click(function() {
                var menu = $('#main-menu .navbar-nav.menu');

                if (!menu.hasClass('hidden') && menu.is(':visible')) {
                    menu.addClass('hidden');
                }
                else menu.removeClass('hidden').show();
            });
        });
    </script>
</body>
</html>
