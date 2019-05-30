@php $theme = theme() @endphp
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JCI | @title()</title>
 
    <link rel="shortcut icon" href="{{ URL::to('_jci-favicon.ico') }}">

     <!-- css files -->
    {{-- css files only! --}}
    @assets('css')  
    <link media="all" type="text/css" rel="stylesheet" href="/css/jci/theme.css" />
    <link media="all" type="text/css" rel="stylesheet" href="/css/jci/responsive.css" />  
    
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

     <!-- js blocks -->
     @assets('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.0.3/jquery.floatThead.js"></script>
</head>
<body class="@body_class() {{ Auth::user()->is_adviser ? 'adviser' : 'client' }} ">
<!-- Navigation -->
<header class="bem-header">
   <div class="bem-header__top bem-header__top_light-brown">
        <div class="container"> 
            <div class="bem-row"> 
               <ul class="bem-contact-details__container">
                    <li class="bem-contact-details_phone-number">
                        <span><i class="fa fa-phone"></i> 1300 605 061</span>
                    </li>
                    <li class="bem-contact-details_email">
                        <a href="mailto:info@justcoffeeinsurance"><span><i class="fa fa-envelope"></i> info@justcoffeeinsurance.com.au</a></span>
                    </li>
               </ul>                              
               <!-- <div class="bem-notifier__container">
                  <a href="#">
                  <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
                  </a>
                  <span class="bem-notifier__icon-bubble">4</span>
               </div> -->
            </div>
        </div>
   </div>
   <div class="bem-header__bottom bem-header__bottom_maroon">
      <!-- <div class="container">
          <div class="row">
            <div class="bem-logo__image">
                  <a href="/">
                    <img src="{{ url("/images/logo-jci.jpg") }}" alt="Just Coffee Insurance" />
                  </a>
               </div>   
          </div>
      </div> -->
      <div class="container">
        <div class="row">               
            <nav class="navbar navbar-default navbar-static-top">
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>                  
               </div>
               <div class="collapse navbar-collapse">                      
                   <ul class="nav navbar-nav">
                        <li class="bem-nav-logo__container">
                          <a href="/">
                            <img src="{{ url("/images/sm-logo-jci.jpg") }}" alt="Just Coffee Insurance" />
                          </a>
                        </li>
                        <li>
                            <a href="{{ route('claim-request') }}" {{ Route::currentRouteNamed('claim-request') ? 'class = active' : '' }}>
                                Make a Claim
                            </a>
                        </li>
                        <li>
                            {{-- @todo make organization dynamic --}}
                            <a href="{{ route('quotes.request') }}" {{ Route::currentRouteNamed('quotes.request') || Route::currentRouteNamed('quotes.form') ? 'class = active' : '' }}>
                                Obtain a Quote
                            </a>
                        </li>                        
                        <li>
                            <a href="{{ route('policy-details') }}" {{ Route::currentRouteNamed('policy-details') ? 'class = active' : '' }}>
                                My Policies
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('claims-request-history') }}" {{ Route::currentRouteNamed('claims-request-history') ? 'class = active' : '' }}>
                                Claims History
                            </a>
                        </li>
                        <li>
                          <a href="{{ route('logout') }}">Sign out</a>
                        </li> 
                        <li class="hide">
                           <i class="fa fa-search"></i>
                        </li>
                   </ul>
                   <div class="bem-search-form__container bem-container__center">
                        <form role="search-form" method="get" class="bem-search-form">
                            <input type="search" class="bem-search-form__input" placeholder="Search..." name="s" title="Search for:" autofocus="autofocus" />
                            <span class="bem-search-form__close-icon"><i class="fa fa-close"></i></span>
                        </form>
                    </div>                 
                </div>   
            </nav>
        </div>
      </div>      
   </div>
</header>
<div class="bem-page__container">                        
    <div {{ Route::currentRouteNamed('login') || Route::currentRouteNamed('register-front') ||  Route::currentRouteNamed('inquiries.create')  ? 'class = bem-member-page__heading-container' : 'class = bem-page__heading-container' }}>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                  <h4 class="bem-page__heading-text">@page_title</h4> 
                </div>
            </div>
        </div>
    </div>
    <div class="bem-page__container-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12">                    
                    @yield('content')                            
                </div>
            </div>      
        </div>
    </div>                                              
</div>
<footer class="bem-footer">
    <div class="container">
        <div class="row">
            <div class="bem-footer__copyright col-md-6 pull-left">
                <strong>Copyright</strong> © <strong>2017
                <a href="#">JCI</a></strong>. All rights reserved.
            </div>
            <div class="bem-footer__version col-md-6 pull-right">
                <p class="bem-text_right"><strong>Version </strong>1.0</p>
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
<script type="text/javascript">
    jQuery( document ).ready(function(  ) {
      
        var jciLogoImg = $('.bem-logo__image a img'), jciLogoOrigHeight = jciLogoImg.height();
        function adjustHeader(loc, height){
          loc = ($(".bem-page__heading-container").offset().top - $(window).scrollTop());
          jciLogoImg_height = (loc > 0 ? (loc/250 - .35) : 0) * height;
          //if(loc <= 200){
          if(jciLogoImg_height < 100){
            if(jciLogoImg_height <= 75)
              jciLogoImg.animate({ 'height': 0 }, 0);
            else
            jciLogoImg.animate({ 'width':'auto', 'height': jciLogoImg_height }, 0);
          }else
            jciLogoImg.animate({ 'opacity': 1, 'width': 155, 'height': 160 }, 0);
        }
        /*adjustHeader(null, jciLogoImg.height());
        $(window).scroll(function() {
            // Check if browser width is greater than 768px
            if($(window).width() > 768) {
                adjustHeader(null, jciLogoOrigHeight);
                //if(jciLogoImg.offset().top > 250) {
                //    // Scale logo image and fade out on scroll to bottom
                //    jciLogoImg.animate({ 'opacity' : 0, 'width': 0, 'height': 0 }, 30);
                //} else {
                //    // Fade in and scale logo image son scroll to top 
                //    jciLogoImg.animate({ 'opacity': 1, 'width': 155, 'height': 160 }, 30);
                //}
            }
        });*/

        var searchForm = $('.bem-search-form__container');
        var navBar = $('.bem-header__bottom .navbar-nav');
        var footerBar = $('.bem-footer');

        searchForm.width(navBar.width()); // Set width of searchform from navbar width by default   
    
        /*if($('.bem-page__container').height() > 915) {
            footerBar.css({
              'margin-top': '30px',
              'position': 'relative'
            }); // Set styles if content container is greater than 560 
        } else {
            footerBar.css({
              'margin': '0',
              'position': 'absolute'
            });
        }*/
        
        $(window).resize(function() {          
          // Check if window width is less than 1024
          if($(window).width() < 1024) {
              // Set width of searchform from navbar width on resize
             searchForm.width(navBar.width()); 
          } else {
             // Set width of searchform from navbar width on resize
             searchForm.width(navBar.width()); 
          }

          // Set styles if content container is greater than 560 on resize
          /*if($('.bem-page__container').height() > 915) {
            footerBar.css({
              'margin-top': '30px',
              'position': 'relative'
            });
          } else {
            footerBar.css({
              'margin': '0',
              'position': 'absolute'
            });
          }*/
        });  

        $('.bem-header__bottom .fa-search').click(function() {
            // Show search form on slide down behavior
            searchForm
                .animate({ top: '25px', opacity: 1 }, 800)
                .css({
                    'overflow': 'visible'
                });
            // Hide navbar slowly       
            navBar
                .animate({ opacity: 0 }, 800)
                .css('display', 'none');
        });

        $('.bem-search-form__close-icon .fa-close').click(function() {          
            // Hide search form on fade out like effect 
            searchForm
                .animate({ top: '-10px', opacity: 0 }, 800)
                .css({
                    'overflow': 'hidden'
            }); 

            // Display navbar slowly    
            navBar
                .animate({ opacity: 1 }, 800)
                .css('display', 'inline-block');            
        }); 

    });
</script>
</body>
</html>
