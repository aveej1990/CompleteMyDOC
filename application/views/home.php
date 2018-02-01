<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->
<!-- Total Size of the Customers , Clients , Projects etc... -->
<?php 

	$totalEmployees		  				 =  $this->timesheet_login->getEmployees(); // Total count of employees
	
	$totalClients  		 			      =  $this->client_model->getClients(); // Total count of clients
	
	$totalProjects  	 			      =  $this->project_model->getProjects(); // Total count of projects
	
	$totalTask     	     				  =  $this->task_model->getTaskList(); // Total count of projects
	
	$totalDevloperReportLog     	      =  $this->emptimelog_model->getRecords($this->session->userdata['logged_in_timesheet']['user_type']); // Total count of projects
	
	$totalDevloperReportLogList     	      =  $this->emptimelog_model->getRecentApprovedReportLog($this->session->userdata['logged_in_timesheet']['user_type']); // Total count of projects
   
  
    date_default_timezone_set('Asia/Kolkata');  // set the default timezone to use. Available since PHP 5.1
 
	$today = date("F j, Y, g:i a");   // Today Date
	
	$day = date("l");
?>
<!-- Total Size of the Customers , Clients , Projects etc... -->
<?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'admin' || $this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>
<div class="content-wrapper" ng-app="getRecentDataApp">
  <div class="page-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>Dashboard</h1>
    </div>
    
  </div>
  <div class="row" >
    
	<?php if($this->session->userdata['logged_in_timesheet']['user_type'] != 'manager'): ?>
	<div class="col-lg-2 col-sm-6">
      <div class="circle-tile"> <a href="<?php echo base_url('employee');?>">
        <div class="circle-tile-heading dark-blue"> <i class="fa fa-users fa-fw fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content dark-blue">
          <div class="circle-tile-description text-faded"> Manage Employees </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalEmployees);?></span> ) <span id="sparklineA"></span> </div>
          <a href="<?php echo base_url('employee');?>" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>
	<?php endif;?>
    <?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>   
	<div class="col-lg-3 col-sm-6">
   <?php else: ?>
	<div class="col-lg-2 col-sm-6">
   <?php endif; ?>
      <div class="circle-tile"> <a href="<?php echo base_url(); ?>clients">
        <div class="circle-tile-heading green"> <i class="fa fa-user-plus fa-fw fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content green">
          <div class="circle-tile-description text-faded"> MANAGE CLIENTS </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalClients);?></span> )  </div>
          <a href="<?php echo base_url(); ?>clients" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>
     <?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>   
	<div class="col-lg-3 col-sm-6">
   <?php else: ?>
	<div class="col-lg-2 col-sm-6">
   <?php endif; ?>
      <div class="circle-tile"> <a href="<?php echo base_url('projects');?>">
        <div class="circle-tile-heading orange"> <i class="fa fa-clone fa-fw fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content orange">
          <div class="circle-tile-description text-faded"> PROJECTS </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalProjects);?></span> ) </div>
          <a href="<?php echo base_url('projects');?>" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>
    <?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>   
	<div class="col-lg-3 col-sm-6">
   <?php else: ?>
	<div class="col-lg-2 col-sm-6">
   <?php endif; ?>
      <div class="circle-tile"> <a href="<?php echo base_url('task');?>">
        <div class="circle-tile-heading blue"> <i class="fa fa-tasks fa-fw fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content blue">
          <div class="circle-tile-description text-faded"> TASKS </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalTask);?></span> ) <span id="sparklineB"></span> </div>
          <a href="<?php echo base_url('task');?>" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>

	<?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>   
	<div class="col-lg-3 col-sm-6">
   <?php else: ?>
	<div class="col-lg-4 col-sm-6">
   <?php endif; ?>
      <div class="circle-tile"> <a href="<?php echo base_url('empreports');?>">
        <div class="circle-tile-heading purple"> <i class="fa fa-indent fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content purple">
          <div class="circle-tile-description text-faded"> Timesheet Logs </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalDevloperReportLog);?></span> )  </div>
          <a href="<?php echo base_url('empreports');?>" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>	
	<div class="col-md-9 col-lg-8">
    <div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <h4 class="panel-title">Recent Clients</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive"  ng-controller="clientCtrl">
                <table class="table table-bordered table-primary nomargin table-hover">
                  <thead>
                    <tr>
                      <th class="text-center">Sno</th>
                      <th>Client Name</th>
                      <th>Email</th>
					  <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                   <tr ng-repeat="client in clients">
                      <td class="text-center">{{$index+1}}</td>
                      <td>{{client.client_name | capitalize:true}}</td>
                      <td>{{client.client_email}}</td>
                      <td><span class="label label-success">{{client.status | capitalize:true}}</span></td>
                    </tr>
			      </tbody>
                </table>
              </div><!-- table-responsive -->
            </div>
          </div><!-- panel -->
        </div>
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <h4 class="panel-title">Recent Projects</h4>
              
            </div>
            <div class="panel-body">
              <div class="table-responsive" ng-controller="projectCtrl">
                <table class="table table-bordered table-primary table-striped nomargin table-hover">
                  <thead>
                    <tr>
                      <th class="text-center">Sno</th>
                      <th>Project Name</th>
                      <th>Client Name</th>
					  <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
				     <tr ng-repeat="project in projects">
                      <td class="text-center">{{$index+1}}</td>
                      <td>{{project.project_name | capitalize:true}}</td>
                      <td>{{project.client_name | capitalize:true}}</td>
					  <td>
						<span ng-if="project.status ==='Process'" class="label label-success">{{project.status | capitalize:true}}</span>
						<span ng-if="project.status ==='Pending'" class="label label-warning">{{project.status | capitalize:true}}</span>
						<span ng-if="project.status ==='Closed'" class="label label-danger">{{project.status | capitalize:true}}</span>
					  </td>
                    </tr>
				</tbody>
                </table>
              </div><!-- table-responsive -->
            </div>
          </div><!-- panel -->
        </div>
      </div>
  </div>
  <div class="col-md-3 col-lg-4 dash-right">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-danger panel-weather">
              <div class="panel-heading" style="background-color:#d9534f;">
                <h4 class="panel-title" style="color:#FFF; font-weight:bold;">Today</h4>
              </div>
              <div class="panel-body inverse" style="background-color:#3b4354;">
                <div class="row mb10">
                  <div class="col-xs-12" style="text-align:center; color:#FFF;">
                    <h1><?php echo $day; ?></h1>
                    <h3><?php echo $today; ?></h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- col-md-12 -->
        </div>
        <!-- row -->
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-success">
              <div class="panel-heading" style="background-color:#259dab;">
                <h4 class="panel-title" style="color:#FFF; font-weight:bold;">Recent Employees</h4>
              </div>
              <div class="panel-body">
                <ul class="media-list user-list" ng-controller="employeeCtrl">
                  
				  <li class="media bline" ng-repeat="user in employees">
                    <div class="media-left"><i class="fa fa-user-plus fa-2x"></i></div>
                    <div class="media-body">
                      <h4 class="media-heading nomargin"><a href="#">{{user.name | capitalize:true}}</a></h4>
                      <small class="date"><i class="fa fa-briefcase"></i>  {{user.designation | capitalize:true}}</small> </div>
                  </li>
				
                  </ul>
              </div>
            </div>
            <!-- panel -->
          </div>
        </div>
        <!-- row -->
      </div>
  </div>
   
</div>
<?php elseif($this->session->userdata['logged_in_timesheet']['user_type'] == 'developer'): ?>
<div class="content-wrapper" ng-app="getRecentDataApp">
  <div class="page-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>Dashboard</h1>
    
  </div>
  <div class="row" >
    
    <div class="col-lg-6 col-sm-6">
      <div class="circle-tile"> <a href="<?php echo base_url('empreports');?>">
        <div class="circle-tile-heading green"> <i class="fa fa-indent fa-3x"></i> </div>
        </a>
        <div class="circle-tile-content green">
          <div class="circle-tile-description text-faded"> Timesheet Logs </div>
          <div class="circle-tile-number text-faded"> ( <span class="count"><?php echo count( $totalDevloperReportLog);?></span> )  </div>
          <a href="<?php echo base_url('empreports');?>" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a> </div>
      </div>
    </div>
   
    
    <div class="col-lg-6 col-sm-6">
      <div class="circle-tile"> 
        <div class="circle-tile-heading red"> <i class="fa fa-clock-o fa-fw fa-3x"></i> </div>
        
        <div class="circle-tile-content red">
          <div class="circle-tile-description text-faded"> <?php echo $day; ?> </div>
          <div class="circle-tile-number text-faded"><?php echo $today; ?> <span id="sparklineC"></span> </div>
          <a href="#" class="circle-tile-footer"><i class="fa fa-clock-o fa-fw fa-1x"></i></a> </div>
      </div>
    </div>
    	
	<div class="col-md-12 col-lg-12">
    <div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <h4 class="panel-title">Recent Added Report Log</h4>
            </div>
            <div class="panel-body">             
			 <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>Sno</th>
                  <th>Name</th>
				  <th>Client Name</th>
				  <th>Project Name</th>
				  <th>Task Name</th>
				  <th>Hours</th>
				  <th>Status</th>
                  <th>Date</th>                 
			    </tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  foreach ($totalDevloperReportLogList as $key => $reportResult) :
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;
					$getListOfProjects   	= $this->emptimelog_model->getAddedReportTaskNames($reportResult->task_Id); // List of tasks	
				  ?>
                <tr <?php echo $showRowColour; ?> id="delRecordsRow<?php echo $reportResult->emp_record_id; ?>">
                  <td><?php echo $i ?></td>
                  <td nowrap="nowrap"><span class="label label-info"><?php echo ucfirst($reportResult->name);?></span></td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->client_name);?> </td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->project_name);?> </td>
				  <td nowrap="nowrap"><?php echo $getListOfProjects;?> </td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->emp_time_hours);?> </td>				  
                  <td nowrap="nowrap"><span class="label label-success"><?php echo $reportResult->status;?></span></td>
                  <th nowrap="nowrap"><?php echo date('d-M-Y',strtotime($reportResult->emp_report_dates));?></th>
                  </tr>
                <?php $i++; endforeach; ?>
              </tbody>
            </table>
            </div>
          </div><!-- panel -->
        </div>
        
      </div>
  </div>
  
  </div>
   
</div>
<?php endif; ?>

<script>

var clientapp = angular.module('getRecentDataApp', []).filter('capitalize', function() {
    return function(input, all) {
      var reg = (all) ? /([^\W_]+[^\s-]*) */g : /([^\W_]+[^\s-]*)/;
      return (!!input) ? input.replace(reg, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
clientapp.controller('clientCtrl', ['$scope', '$http', function ($scope, $http) { // Get Recent Clients
 $http({
  method: 'get',
  url: 'clients/getRecentClients'
 }).then(function successCallback(response) {
  // Store response data
  $scope.clients = response.data;
 });
}]).controller('projectCtrl', ['$scope', '$http', function ($scope, $http) {   // Get Recent Projects
 $http({
  method: 'get',
  url: 'projects/getRecentProjects'
 }).then(function successCallback(response) {
  // Store response data
  $scope.projects = response.data;
 });
}]).controller('employeeCtrl', ['$scope', '$http', function ($scope, $http) { // Get Recent Customers 
 $http({
  method: 'get',
  url: 'employee/getRecentEmployees'
 }).then(function successCallback(response) {
  // Store response data
  $scope.employees = response.data;
 });
}]);

</script>
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
