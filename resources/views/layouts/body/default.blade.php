<header id="header" class="container">
  <div id="logo">
    {{-- should we make this part dynamic ? --}}
    <a href="{{ route('home') }}">
      {{Html::image('images/logo-uis-white.png', 'Ultra Insurance Solution', ['id'=>'logo-main'])}}
    </a>    
  </div>

  <nav id="main-menu" class="menu">
    {{-- 
       call push('nav-main-menu') (<LI> menu item *) endpush
    --}}
    @stack('nav-main-menu')
  </nav>
</header>

<aside id="sidebar-left">
    @stack('sidebar-left')
</aside>

<section id="content" class="container">
    <h2 id="page-title">@page_title()</h2>

    <div id="messages">@include('flash::message')</div>

    <div id="page-content">
        @yield('content')     
    </div>
</section>