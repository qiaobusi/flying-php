<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
			<img src="{{ asset("assets/AdminLTE/dist/img/user2-160x160.jpg")  }}" class="img-circle">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
		<li class="header">MAIN NAVIGATION</li>
		<li class="active treeview">
		  <a href="#">
			<i class="fa fa-dashboard"></i> <span>管理员</span>
			<span class="pull-right-container">
			  <i class="fa fa-angle-left pull-right"></i>
			</span>
		  </a>
		  <ul class="treeview-menu">
			<li><a href="{{ url('manage/manager/index') }}"><i class="fa fa-circle-o"></i>管理员</a></li>
		  </ul>
		</li>
		<li class="active treeview">
		  <a href="#">
			<i class="fa fa-dashboard"></i> <span>用户</span>
			<span class="pull-right-container">
			  <i class="fa fa-angle-left pull-right"></i>
			</span>
		  </a>
		  <ul class="treeview-menu">
			<li><a href="{{ url('manage/user/index') }}"><i class="fa fa-circle-o"></i>用户</a></li>
		  </ul>
		</li>
	  </ul>
      
    </section>
    <!-- /.sidebar -->
  </aside>