@php $theme = Auth::check() && Auth::user()->is_client ? 'jci' : 'cmi' @endphp
<!DOCTYPE html>
<html lang="en" class="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @title()</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/_cmi-favicon.ico"> 
    
    <!-- css files -->
    {{-- css files only! --}}    
    @assets('css')

    <link media="all" type="text/css" rel="stylesheet" href="/css/cmi/theme.css" />
    <link media="all" type="text/css" rel="stylesheet" href="/css/cmi/responsive.css" />   

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
<body class="@body_class() {{ !Auth::check() ? 'guest' : (Auth::user()->is_adviser ? 'adviser' : 'client') }} bem-background__container-blue-white-horizontal-half">   
    <div class="bem-header">
        <div class="row">
            <div class="upper">            
                <div class="bem-inquiry__header-container_top">
                    <div class="bem-inquiry__header-container_logo">   
                        <a href="{{ url("/") }}">
                            <img class="bem-inquiry__header-container_logo-image bem__image_center" src="{{ url("/images/logo-{$theme}.png") }}" alt="CMI Data">
                        </a>
                    </div>                    
                </div>          
            </div> 
        </div>             
    </header>
    <div class="container-fluid">
        <div class="row">
            @yield('body')
        </div>
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
