<div class="wrapper">
  
  <header class="main-header">
    <a href="{{ url('/') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
        {{Html::image('images/logo-uis-white.png', 'Ultra Insurance Solution')}}
      </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        {{Html::image('images/logo-uis-white.png', 'Ultra Insurance Solution')}}
      </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   <images src="{{ url('/images/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ $user = Auth::user()?Auth::user()->first_name.' '.Auth::user()->surname:'' }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../public/images/user2-160x160.jpg" class="img-circle" alt="User Image">

                    <p>
                    {{ $user = Auth::user()?Auth::user()->first_name.' '.Auth::user()->surname:'' }}
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                   @stack('nav-main-menu')        
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>

        </nav>    
  </header>
  <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar" style="height: auto;">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <images src="{{ url('images/user2-160x160.jpg') }}" class="images-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ $user = Auth::user()?Auth::user()->first_name.' '.Auth::user()->surname:'' }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
              <li class="header">MAIN NAVIGATION</li>
              <li class="active treeview">
                <a href="/">
                  <i class="fa fa-user"></i> <span>Quotes</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li> 
                    <a href="#" data-toggle="modal" data-target="#new_quote">
                      <i class="fa fa-plus"></i>
                      <span>New Quote</span>
                    </a>
                  </li>     
                  <hr class="divider-recent-record" />
                  <!--  A maximum of 3 records will be shown -->                          
                  <span class="text-left text-white text-uppercase" style="margin-left: 15px">Recent Records</span>                      
                  <li>
                    <a href="#">                
                      <span>Internet Explorer 4.0</span>
                    </a>
                  </li>   
                  <li>
                    <a href="#">                
                      <span>Firefox 1.0</span>
                    </a>
                  </li> 
                  <li>
                    <a href="#">                
                      <span>Netscape Browser 8</span>
                    </a>
                  </li>   
                </ul>
              </li>
              <li class="treeview">
                <a href="{{ route('profiles.index') }}">
                  <i class="fa fa-users"></i> <span>Client Profiles</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"> Recommendations</a></li>
                  <li class="active"><a href="#"><i class="fa fa-caret-down"></i> Current Policies</a>
                    <ul class="treeview-menu">
                      <li><a href="#"></i> Additional</a></li>
                      <li class="active"><a href="#"> New Policy</a></li>
                    </ul>
                  </li>
                  <li><a href="#"> Expired Policies</a></li>
                  <li><a href="#"> Quotes</a></li>
                  <li><a href="#"> Claims</a></li>
                  <ul class="treeview-menu">
                    <li><a href="#"> Additional</a></li>
                    <li class="active"><a href="#"> Claim History</a></li>
                  </ul>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-file-text"></i>
                  <span>Sample 02</span>
                  <span class="pull-right-container">
                    <span class="label label-primary pull-right">4</span>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 2 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 2 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 2 - Sub Menu 3</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 2 - Sub Menu 4</a></li>
                </ul>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-file-excel-o"></i> <span>Sample 03</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-green">new</small>
                  </span>
                </a>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-file-text-o"></i>
                  <span>New Quote</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 4 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 4 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 4 - Sub Menu 3</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 4 - Sub Menu 4</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-cc"></i>
                  <span>Sample 04</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 3</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 4</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 5</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 5 - Sub Menu 6</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-pencil-square-o"></i>
                  <span>Sample 05</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 6 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 6 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 6 - Sub Menu 3</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-pencil-square"></i>
                  <span>Sample 06</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 7 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 7 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 7 - Sub Menu 3</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-users"></i>
                  <span>Sample 07</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 8 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 8 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 8 - Sub Menu 3</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-list-alt"></i>
                  <span>Sample 08</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 9 - Sub Menu 1</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 9 - Sub Menu 2</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Menu 9 - Sub Menu 3</a></li>
                </ul>
              </li>              
            </ul>       
      </section>
    <!-- /.sidebar -->
  </aside>      
  <div class="content-wrapper">