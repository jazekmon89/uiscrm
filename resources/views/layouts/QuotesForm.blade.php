@php $theme = theme() @endphp
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

    @if(isset($css) && count($css))
        @foreach($css as $i)
            <style id="{{ $i['1'] }}">
            @includeif($i['0'], (array_key_exists('2', $i)?$i['2']:[]))
            </style>
        @endforeach
    @endif

    <link href="/css/_{{ $theme }}/_layout.css" rel="stylesheet"> 
    <link href="/css/_{{ $theme }}/_media.css" rel="stylesheet"> 
    <link href="/css/_{{ $theme }}/_utils.css" rel="stylesheet"> 

    <!-- css blocks -->
    {{-- css blocks only! --}}
    @assets('cssblock')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]); !!}
    </script>
    @js('js/stickysidebar/stickysidebar.js', 'stickysidebarjs', 'stickysidebar')
    @assets('js')

    <style>
        #content{margin-bottom: 20px;}
        .client #header #main-menu .navbar-nav.menu{margin-left:130px;}
        #top-header #logo img{margin: 0;}
        .navbar{min-height: auto;}
    </style>
</head>
<body class="@body_class() {{ !Auth::check() ? 'guest' : (Auth::user()->is_adviser ? 'adviser' : 'client') }} ">
    <header id="header">
        <div class="upper">
            <div class="container">
                <div id="top-header">
                    <div class="row">
                        <div class="col-md-2" id="logo">
                            <a href="{{ url("/") }}">
                                <img src="{{ url("/images/logo-{$theme}.jpg") }}">
                            </a>
                        </div>
                        @if(Auth::check())
                        <div class="menu">
                            <div class="dropdown text-center" id="user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-user fa-2x"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="first">
                                        <a>
                                            {{ Auth::user()->name }} <i class="fa fa-chevron-down"></i>
                                        </a>
                                    </li>
                                    <li class="white">
                                        <a href="{{ route('dashboard') }}">
                                          My Home
                                        </a>
                                    </li>
                                    @if(Auth::user()->is_client)
                                        <li class="white">
                                            <a href="{{ route('inquiries.create') }}">
                                                Submit an Inquiry
                                            </a>
                                        </li>
                                    @endif
                                    <li class="white">{{ link_to_route('logout', "Logout") }}</li>
                                </ul>
                            </div>
                        </div> 
                        <!--div class="notify">
                          <a href="#">
                            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
                          </a>
                           <span class="bubble-notify">4</span>
                        </div-->  
                                       
                       {{-- change column size accordingly --}}
                        @if (Auth::check() && Auth::user()->is_adviser)
                        <div class="search search-form">
                           @if (Auth::user())
                                <nav class="navbar navbar-default" style="border:0;margin:0;padding:0">
                                {{ Form::open(['route' => ['search.details'], 'method' => 'get', 'class' => 'navbar-form navbar-left', 'style' => 'margin: 0;padding:0;']) }}
                               
                                <div class="form-group">

                                     <select name="Search[Field]" class="form-control">
                                            <optgroup label="Insurance Details">
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.BrokerRefNum">Reference No.</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.InsuredName" selected>Insured Name</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.CompanyName">Company Name.</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.PolicyNum">Policy No.</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.QuoteNum">Quote No.</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.InvoiceNum">Invoice No.</option>
                                                <option value="FindInsuranceEntitiesByInsuranceDetails.MotorVehicleRegNum">Motor Vehicle Reg.</option>
                                            </optgroup>
                                            <optgroup label="Contact Details">
                                                <option value="FindContactByPersonalDetails.ContactRefNum">Reference No.</option>
                                                <option value="FindContactByPersonalDetails.FirstName">First Name</option>
                                                <option value="FindContactByPersonalDetails.MiddleNames">Middle Names</option>
                                                <option value="FindContactByPersonalDetails.Surname">Last Name</option>
                                                <option value="FindContactByPersonalDetails.PreferredName">Preferred Name</option>
                                                <option value="FindContactByPersonalDetails.EmailAddress">Email Address</option>
                                                <option value="FindContactByPersonalDetails.MobilePhoneNumber">Motor Vehicle Reg.</option>
                                            </optgroup>
                                        </select>

                                    {{ Form::jInput("text", "Search.Input", null, ['placeholder' => 'Search...']) }}
                                    
                                    <label >
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </label>
                                    
                                </div>
                                
                               
                                {{ Form::close() }}
                                </nav>
                            @endif
                        </div>
                             @endif
                        @endif                        
                    </div>
                </div>
            </div>
        </div>

        <div class="lower">
            <div class="container">
                <div class="" id="main-menu">
                    <nav class="navbar navbar-default navbar-static-top">

                            <div class="navbar-header hidden col-md-2">
                              <a class="navbar-brand" href="#">
                                  <i class="fa fa-bars fa-2x"></i>
                              </a>
                            </div>
                            @if (Auth::check() && Auth::user()->is_adviser)
                                <ul class="nav navbar-nav navbar-right hidden col-md-10 search-nav">
                                    <li>
                                        <div class="search-form">
                                            @if (Auth::check())

                                                {{ Form::open(['route' => ['search.details']]) }}
                                                    <div class="input-group form-group col-md-12">
                                                        <input type="text" placeholder="Search" class="form-control">
                                                        <span class="input-group-btn">
                                                        <button class="btn"><i class="fa fa-search "></i></button>
                                                        </span>
                                                    </div>
                                                {{ Form::close() }}
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            @endif
                            <ul class="nav navbar-nav menu">                                                         
                                @if(!Auth::check())
                                    <li {{ Route::currentRouteNamed('quotes.request') ? 'class = active-menu' : '' }}>
                                        {{-- @todo make organization dynamic --}}
                                        <i class="fa fa-google-plus"></i><a href="{{ route('quotes.request') }}">
                                            Obtain a Quote
                                        </a>
                                    </li>
                                    <li>
                                        <i class="fa fa-google-plus"></i><a href="{{ route('inquiries.create') }}">
                                            Inquire Us
                                        </a>
                                    </li>
                                @else
                                    @if(Auth::check() && Auth::user()->is_adviser)
                                        <!--
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Client Profiles <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('client.profiles') }}">Visit Client Profile</a></li>
                                                <li><a href="#">Recommended Covers</a></li>
                                                <li><a href="#">Current Policies</a></li>                                                   
                                                <li><a href="#">Expired Policies</a></li>
                                                <li><a href="#">Quotes</a></li>
                                                <li><a href="#">Claims</a></li>
                                            </ul>
                                        </li>
                                        -->
                                        <li {{ Request::is('home') ? 'class = active-menu' : '' }}>
                                            <a href="{{ url("/") }}">Home</a>
                                        </li>
                                        <li {{ Route::currentRouteNamed('client.profiles') || Route::currentRouteNamed('client.recommendations') || Route::currentRouteNamed('client.show-contacts') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('client.profiles') }}">General Insurance</a>
                                        </li>
                                        <li>
                                            <a href="#" class="link-disabled">Financial Planning</a>
                                        </li>
                                        <li>
                                            <a href="#" class="link-disabled">Risk Assessment</a>
                                        </li>
                                        <li>
                                            <a href="#" class="link-disabled">Accounting</a>
                                        </li>
                                        <li>
                                            <a href="#" class="link-disabled">Stock Broking</a>
                                        </li>
                                        <li>
                                            <a href="#" class="link-disabled">Mortgage</a>
                                        </li>
                                       <!--  <li {{ Route::currentRouteNamed('client.profiles') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('client.profiles') }}">Client Profile</a>
                                        </li>
                                        <li {{ Route::currentRouteNamed('quotes.index') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('quotes.index') }}">
                                                Current Quotes
                                            </a>
                                        </li>
                                        <li {{ Route::currentRouteNamed('quotes.index.expired') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('quotes.index.expired') }}">

                                                Expired Quotes
                                            </a>
                                        </li> -->
                                        <!--<li><a href="#">New Quote</a></li>-->
                                        <!-- <li>
                                            <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">New Quote <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Quote Form</a></li>                                                      
                                            </ul>
                                        </li> -->
                                         <!-- <li>
                                            <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Current Renewals <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Renewal Process</a></li>                                                      
                                            </ul>
                                        </li>     -->                                  
                                        <!-- <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Current Claims</a></li>
                                                <li><a href="#">Finalized Claims</li>                                              
                                                <li><a href="#">Lodge Claim</a></li> 
                                                <li><a href="#">Payment Details</a></li>             
                                            </ul>
                                        </li>     -->                        
                                    @else
                                        <!-- <li>
                                            <a href="#">
                                                My Insurance Covers
                                            </a>
                                        </li> -->
                                        <li {{ Route::currentRouteNamed('claim-request') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('claim-request') }}">
                                                Make a Claim
                                            </a>
                                        </li>
                                        <li {{ Route::currentRouteNamed('quotes.request') ? 'class = active-menu' : '' }}>
                                            {{-- @todo make organization dynamic --}}
                                            <a href="{{ route('quotes.request') }}">
                                                Obtain a Quote
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="#">
                                                Certificate of Currency
                                            </a>
                                        </li> -->
                                        <li {{ Route::currentRouteNamed('policy-details') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('policy-details') }}">
                                                My Policies
                                            </a>
                                        </li>
                                        <li {{ Route::currentRouteNamed('claims-request-history') ? 'class = active-menu' : '' }}>
                                            <a href="{{ route('claims-request-history') }}">
                                                Claims History
                                            </a>
                                        </li>
                                        <!-- <li {{ Route::currentRouteNamed('quotes.request') ? 'class = active-menu' : '' }} >
                                            <a href="#">
                                                Test
                                            </a>
                                        </li> -->
                                        <!--li><a> <i class="fa fa-chevron-down"></i> </a></li -->
                                    @endif
                                @endif
                            </ul>

                            @if(!Auth::check())
                                <ul class="nav navbar-nav navbar-right guest-menu col-md-3">
                                    <li>
                                        <a href="{{ route('login') }}">
                                            Sign In
                                        </a>
                                    </li>
                                     <li>
                                         <a href="{{ route('register-front') }}">
                                            Sign Up
                                        </a>
                                    </li>
                                </ul>
                            @endif
                    </nav>
                </div>
            </div>
        </div>
    </header> 
    @if (!Route::currentRouteNamed('dashboard') && isset($ClientID) && Auth::user()->is_adviser) 
        <div class="row">
            <div class="col-md-2">
                <aside class="main-sidebar">
                  <!-- sidebar: style can be found in sidebar.less -->
                  <section class="sidebar" style="height: auto;">        
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <!--li class="header">LEFT NAVIGATION</li-->
                        <li>
                            <a href="{{ route('dashboard') }}">
                              <i class="fa fa-home"></i> <span>Client Home</span>                  
                            </a>
                        </li>         
                        <li {{ url('client/profiles') ? 'class = active-menu' : '' }}>
                            <a href="{{ url('client/profiles') }}">
                              <i class="fa fa-book"></i><span>Client Profile</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-m menu-open">
                              <li><a href="{{ isset($ClientID)?route('client.recommendations',[$ClientID]):'#' }}" {{ Route::currentRouteNamed('client.recommendations') ? 'class = active' : '' }}></i>Recommendations</a></li>
                              <li><a href="{{ isset($ClientID)?route('client.show-contacts',[$ClientID]):'#' }}" {{ Route::currentRouteNamed('client.show-contacts') ? 'class = active' : '' }}>Contacts</a></li>                 
                            </ul>
                        </li>
                        <li class="#">
                            <a href="#">
                              <i class="fa fa-bullhorn"></i> <span>Policies</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-m menu-open">
                              <li><a href="#">Renewals</a></li>
                              <li><a href="#">Comparison</a></li> 
                              <li><a href="#">History</a></li>                  
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                              <i class="fa fa-file-excel-o"></i> <span>RFQ's</span>                  
                            </a>
                        </li>
                       <li class="#">
                            <a href="#">
                              <i class="fa fa-quote-left"></i> <span>Quotes</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-m menu-open">
                                <li><a href="#">Comparison</a></li>   
                                <li><a href="#">History</a></li>                  
                            </ul>
                        </li>             
                        <li class="#">
                            <a href="#">
                              <i class="fa fa-file-text"></i> <span>Claims</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-m menu-open">
                              <li><a href="#">History</a></li>                 
                            </ul>
                        </li>             
                    </ul>       
                  </section>
                    <!-- /.sidebar -->
                </aside>   
            </div>    
    @endif
    @if (!Route::currentRouteNamed('dashboard') && isset($ClientID) && Auth::user()->is_adviser) 
            <div class="col-md-10">
    @endif        
                <div class="cont-wrapper">
                    @include('flash::message')
                    <div id="page-title" class="container">
                       <div class="row">
                            <div class="title col-md-7">
                                <h4 class="grid-100" style="margin: 0">@page_title()</h4>    
                                <div class="grid-100 v-space-2x ">
                                    @dynamicblock('title-bottom')
                                </div>
                            </div>        
                            
                            <div class="toolbars col-md-5 text-right">
                                @dynamicblock('title-aside')
                            </div>
                        </div>
                    </div>
                    <div id="content" {{ Route::currentRouteNamed('client.profiles') ? 'class = cstm-col-offset-2' : 'class = container' }}>
                        @if($document->getPageLayout() === 'box')
                            <div class="white-box full-box">
                                @yield('body')
                            </div>
                        @else
                            <div class="white-box grid-box">
                                @yield('body')    
                            </div>
                        @endif
                    </div>
                </div>
    @if (!Route::currentRouteNamed('dashboard')  && isset($ClientID) && Auth::user()->is_adviser)          
            </div>    
         </div>   
    @endif         
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 pull-left">
                    <strong>Copyright</strong> &#169; <strong>2017
                    <a href='#'>CMI Data</a></strong>. All rights reserved.
                </div>
                <div class="col-md-6 pull-right">
                    <p class="text-right"><strong>Version </strong>1.0</p>
                </div>
            </div>
        </div>
    </footer>
   
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
                    //c.height(v - (h.outerHeight() + 50 + f.outerHeight()));
                }
                else {
                    //c.height("auto");
                }

            })();

            $(window).resize(function() {
                //setTimeout(adjust, 100);
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
