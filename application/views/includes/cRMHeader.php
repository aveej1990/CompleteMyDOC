<?php header('Content-Type: text/html; charset=ISO-8859-1');  ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?php echo HTTP_IMAGES_PATH;?>ico/favicon.png">
<!-- CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CSS_PATH; ?>main.css">
<link href="<?php echo HTTP_CSS_PATH; ?>timeline.css" rel="stylesheet"/>
<script src="<?php echo HTTP_JS_PATH; ?>jquery-2.1.4.min.js"></script>
<script src="<?php echo HTTP_JS_PATH; ?>jquery-migrate-1.2.1.js"></script>
<script src="<?php echo HTTP_JS_PATH; ?>jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CSS_PATH; ?>jquery-ui.css">
<script src="<?php echo HTTP_JS_PATH; ?>jquery.validate.min.js"></script>
<script src="<?php echo HTTP_JS_PATH; ?>angular.min.js"></script>
<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/select2.min.js"></script>
<title>eLogic Timesheet</title>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
<!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
</head>
<body class="sidebar-mini fixed">
<div class="wrapper">
<!-- Navbar-->
 <?php if(!empty($this->session->userdata['logged_in_timesheet']['username'])): 
		    $userDetails =  $this->timesheet_login->user_information($this->session->userdata['logged_in_timesheet']['username']);
		    foreach($userDetails as $key) { $fullname = ucwords($key->name); $designation = ucwords($key->designation);}
		  ?>
<header class="main-header hidden-print"><a class="logo" href="<?php echo base_url(); ?>"><img src="<?php echo HTTP_IMAGES_PATH; ?>main_logo.png" alt="logo"></a>
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
    <!-- Navbar Right Menu-->
    <div class="navbar-custom-menu">
      <ul class="top-nav">
	    <!--Notification Menu-->
		<li><a href="#"><i class="fa fa-comments"></i> Live Chatting</a></li>
		<li><a href="#"><i class="fa fa-bell"></i> Notice</a></li>
		<li><a href="#"><i class="fa fa-calendar"></i> Holidays</a></li>
		<li><a href="<?php echo base_url(); ?>stickynote"><i class="fa fa-commenting-o"></i> Sticky Note</a></li>
        <li><h5 style="color:#FFF;">Welcome <?php echo $fullname; ?></h5></li>
        
        <!-- User Menu-->
        <!-- User Menu-->
        <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu">
            <!-- <li><a href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
                  <li><a href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>-->
            <li><a href="<?php echo base_url(); ?>home/logout"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- Side-Nav-->
<aside class="main-sidebar hidden-print">
  <section class="sidebar">
   
    <div class="user-panel">
      <div class="pull-left image">
	     <?php if(!empty($key->avatar)): ?>
			<img class="img-circle" src="<?php echo base_url().'uploads/employee_pic/'.$key->avatar; ?>" alt="User Image">
		 <?php else: ?>
			<img class="img-circle" src="<?php echo HTTP_IMAGES_PATH; ?>default.jpg" alt="User Image">
		 <?php endif; ?>
		 </div>
      <div class="pull-left info">
        <p><?php echo $fullname; ?></p>
        <p class="designation"><?php echo $designation; ?></p>
      </div>
    </div>
   
    <!-- Sidebar Menu-->
   <ul class="sidebar-menu">
	 <?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'admin'): ?>
      <li class="active"><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
      <li><a href="<?php echo base_url(); ?>employee"><i class="fa fa-users"></i><span>Employees</span></a></li>
      <li><a href="<?php echo base_url(); ?>clients"><i class="fa fa-user-plus"></i><span>Clients</span></a></li>
      <li><a href="<?php echo base_url(); ?>projects"><i class="fa fa-clone"></i><span>Projects</span></a></li>
      <li><a href="<?php echo base_url(); ?>task"><i class="fa fa-tasks"></i><span>Task</span></a></li>
	  <li><a href="<?php echo base_url(); ?>task/taskmaping"><i class="fa fa-tasks"></i><span>Task Mapping</span></a></li>
      <li><a href="<?php echo base_url(); ?>timesheet"><i class="fa fa-clock-o"></i><span>Timesheet</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports"><i class="fa fa-indent"></i><span>Timesheet Logs</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports/searchreportlog"><i class="fa fa-search"></i><span>Search Timesheet Logs</span></a></li>
       
	 <?php elseif($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>
	 <li class="active"><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
	  <li><a href="<?php echo base_url(); ?>clients"><i class="fa fa-user-plus"></i><span>Clients</span></a></li>
      <li><a href="<?php echo base_url(); ?>projects"><i class="fa fa-clone"></i><span>Projects</span></a></li>
      <li><a href="<?php echo base_url(); ?>task"><i class="fa fa-tasks"></i><span>Task</span></a></li>
	  <li><a href="<?php echo base_url(); ?>task/taskmaping"><i class="fa fa-tasks"></i><span>Task Mapping</span></a></li>
      <li><a href="<?php echo base_url(); ?>timesheet"><i class="fa fa-clock-o"></i><span>Timesheet</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports"><i class="fa fa-indent"></i><span>Timesheet Logs</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports/searchreportlog"><i class="fa fa-search"></i><span>Search Timesheet Logs</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports/cpass"><i class="fa fa-key"></i><span>User Information</span></a></li>
	  <?php elseif($this->session->userdata['logged_in_timesheet']['user_type'] == 'developer'): ?>
	  <li class="active"><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
      <li><a href="<?php echo base_url(); ?>empreports"><i class="fa fa-indent"></i><span>Timesheet Logs</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports/searchreportlog"><i class="fa fa-search"></i><span>Search Timesheet Logs</span></a></li>
	  <li><a href="<?php echo base_url(); ?>empreports/cpass"><i class="fa fa-key"></i><span>User Information</span></a></li>
      <?php endif; ?>
	  </ul>
  </section>
</aside>
 <?php endif; ?>