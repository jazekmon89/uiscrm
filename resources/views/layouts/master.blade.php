<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @title()</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Favicon -->
    @if (Auth::check() && Auth::user()->is_adviser)
        <link rel="shortcut icon" href="{{ URL::to('_cmi-favicon.ico') }}">
    @else 
        <link rel="shortcut icon" href="{{ URL::to('_jci-favicon.ico') }}">
    @endif    

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Cabin|Oxygen" rel="stylesheet">

    <!-- css files -->
    {{-- css files only! --}}    
    @assets('css')

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
<body class="@body_class()">   
    
    @section('body')
    @show()

    <footer>
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
    @endif
    </footer>

</body>
</html>
