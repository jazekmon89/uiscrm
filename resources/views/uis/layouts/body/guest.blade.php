<nav class="guest navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header " id="app-navbar-collapse">
      <!-- Right Side Of Navbar -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        @stack('nav-main-menu')
      </ul>
    </div>
  </div>
</nav>
<div class="logo-mainwrap">
    <div id="logo-innerwrap">
        <!-- Branding Image -->
        <a href="{{ url('/') }}">
          {{Html::image('images/logo-uis-white.png', 'Ultra Insurance Solution', ['id'=>'logo-main'])}}
        </a>
    </div>
</div>