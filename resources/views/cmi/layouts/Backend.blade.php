<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app_name() }} | @title()</title>
    <link rel="shortcut icon" href="/_cmi-favicon.ico"> 
    
    @assets('css')

    <link media="all" type="text/css" rel="stylesheet" href="/css/cmi/theme.css" />
    <link media="all" type="text/css" rel="stylesheet" href="/css/cmi/responsive.css" />   

    @assets('cssblock')

    @if(isset($css) && count($css))
        @foreach($css as $i)
            <style id="{{ $i['1'] }}">
            @includeif($i['0'], (array_key_exists('2', $i)?$i['2']:[]))
            </style>
        @endforeach
    @endif

    @assets('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.0.3/jquery.floatThead.js"></script>
    @php  
      $active = isset($active) ? $active : "";
    @endphp
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]); !!}
    </script>
</head>
<body class="@body_class() {{ Auth::user()->is_adviser ? 'bem-page__container-with-sidebar  adviser' : 'client' }} ">
<!-- Navigation -->
<div class="bem-header">
  <nav class="bem-navbar__menu-mobile navbar navbar-default navbar-fixed-top">
    <div class="bem-header__top bem-header__top_white">
      <div class="pull-left">
        <div class="bem-logo__image">
              <a href="/">
                <img src="{{ asset('images/logo-cmi.jpg') }}" alt="{{ app_name() }}" />
              </a>
        </div>
      </div>
      <div class="pull-right">
          <div class="bem-user-menu__container">
            <div class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
               <i class="fa fa-user fa-2x"></i>
               </a>
               <ul class="dropdown-menu">
                  <li class="bem-dropdown-menu_first">
                     <a>
                     {{ Auth::user()->name }} <i class="fa fa-chevron-down"></i>
                     </a>
                  </li>
                  <li class="bem-dropdown-menu_white">
                     <a href="{{ route('dashboard') }}">
                     My Home
                     </a>
                  </li>                       
                  <li class="bem-dropdown-menu_white">
                    <a href="{{ route('logout') }}">Logout</a>
                  </li>
               </ul>
            </div>
         </div>       
      </div>
    </div>
    <div class="bem-header__bottom bem-header__bottom_blue">
      <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>              
      </div>
      <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li {{ Route::currentRouteNamed('dashboard') ? 'class = active' : '' }}><a href="{{ route('dashboard') }}">Home</a></li>
            <li {{ Route::currentRouteNamed('general-insurance') || Request::is('quotes') || Request::is('quote-requests') || Request::is('quote-requests/*') || Request::is('client/*') ? 'class = active' : '' }}><a href="{{ route('general-insurance') }}">General Insurance</a></li>   
            <li><a href="#">Financial Planning</a></li> 
            <li class="bem-header__bottom_menu-mobile"><a href="#">Risk Assessment</a></li>  
            <li class="bem-header__bottom_menu-mobile"><a href="#">Accounting</a></li>   
            <li class="bem-header__bottom_menu-mobile"><a href="#">Stock Broking</a></li> 
            <li class="bem-header__bottom_menu-mobile"><a href="#">Mortgage</a></li>                                               
            <li class="dropdown bem-header__bottom_dropdown-menu-mobile">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i></a>
              <ul class="dropdown-menu">
                <li><a href="#">Accounting</a></li> 
                <li><a href="#">Stock Broking</a></li>   
                <li><a href="#">Mortgage</a></li>                     
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
              <li><span class="bem-user-name-container__text">{{ Auth::user()->name }}</span></li>
          </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav> 
</div>
<div class="container-fluid">
  <div class="row">
    @if(\Request::is('quotes/*') || \Request::is('quote-requests/*') || \Request::is('client/profiles/*') || \Request::is('client/*/recommendations') || \Request::is('client/*/contacts')) 
      <div class="col-sm-3 col-md-2 bem-sidebar__container">
        	@if(!$document->isEmptyGroupBlock('sidebar-left'))
              @dynamicblock('sidebar-left') 
           @endif
      </div>
    @elseif(Request::is('client/profiles'))
      <div></div>  
    @endif
    @if(\Request::is('quote-requests/*') || \Request::is('quotes/*') || \Request::is('client/profiles/*') || \Request::is('client/*/recommendations') || \Request::is('client/*/contacts')) 
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">    
    @endif
      <div class="bem-page__adviser-main-container bem-page__container">
          <div class="bem-page__heading-container">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-9">
                        <h4 class="bem-page__heading-text">@page_title</h4> 
                        @if(!$document->isEmptyGroupBlock('sub-title'))
                          @dynamicblock('sub-title') 
                        @endif
                      </div>
                      <div class="col-md-3 text-right">
                        @if(!$document->isEmptyGroupBlock('title-aside'))
                              @dynamicblock('title-aside')
                        @endif
                      </div>
                  </div>
              </div>
          </div>
          <div class="container-fluid">
            @include("flash::message")
          </div>
          <div class="bem-page__container-row">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-12">                    
                          @yield('content')                            
                      </div>
                  </div>      
              </div>
          </div>
      </div>
    @if(\Request::is('quote-requests/*')) 
    </div>
    @endif

    @if(\Request::is('quotes/*')) 
    </div>
    @endif
    
  </div> 
</div>
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
<script type="text/javascript">
  $(document).ready(function(){
    $('table.table-fixed-header').floatThead({
      position: 'absolute',
      scrollContainer: true
    });
  });
</script>
</body>
</html>