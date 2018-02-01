<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<?php $getUpdateId = $this->uri->segment('3'); // Update Segment ?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Employee Information</h1>
    </div>
  </div>
 <?php if(empty($getUpdateId)): ?> 
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div>
          <h4 class="line-head">Add Employee</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Employees" href="<?php echo base_url('employee');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
		
        <form class="form-horizontal" method="post" name="add_employee" id="add_employee" enctype="multipart/form-data" action="<?php echo base_url('employee/addemployee');?>" ng-app="empUsername" ng-controller="empCtrl">
          <div class="form-group">
            <label class="control-label col-md-3">First Name: <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="fname" id="fname" placeholder="Enter First Name" value="<?php echo set_value('fname'); ?>">
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Last Name: <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" name="lname" id="lname" placeholder="Enter Last Name" value="<?php echo set_value('lname'); ?>">
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Username: <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" ng-model="username" name="username" id="username" placeholder="Enter Username" value="<?php echo set_value('username'); ?>">
			  <?php echo form_error('username'); ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Email: </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" name="email" id="email" placeholder="Enter Email" value="<?php echo set_value('email'); ?>">
			  <?php echo form_error('email'); ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Password: <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="password" name="password" id="password" autocomplete="off" placeholder="Enter Password" value="{{username}}"> 
			  <br/>
			  <span> Note : Displaying Username "{{username}}" same as in password if you want to change you can enter new password</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Designation : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <select class="form-control" id="designation" name="designation">
                <option value="">Please choose designation</option>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
                <option value="Sr.Architect">Sr.Architect</option>
				<option value="jr.Architect">jr.Architect</option>
				<option value="Co-Ordinator">Co-Ordinator</option>
				<option value="Director">CEO & Managing Director</option>
				<option value="COO">COO</option>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">User Type : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <select class="form-control" id="user_type" name="user_type">
                   <option value="">Please choose Type</option>
                   <option value="admin">Admin</option>
                   <option value="manager">Manager</option>
                    <option value="developer">Developer</option>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Image: </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="file" name="employee_image" id="employee_image" placeholder="Upload Photo" onchange="PreviewImage();">
			  <br>
			  <div class="info"><img id="previewImg" class="user-img" width="120" height="120" src="<?php echo HTTP_IMAGES_PATH; ?>default.jpg"></div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('employee');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
            </div>
          </div>
        </form>
		
      </div>
    </div>
  </div>
 <?php else: ?> 
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div>
          <h4 class="line-head">Update Employee</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Employees" href="<?php echo base_url('employee');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
		<?php foreach($updateEmployee as $key => $getEmpDetails) { 
		
		  	$name =  explode(' ' , $getEmpDetails->name); // expload the employee name
		    
			
		 } 
		 
		 ?>
        <form class="form-horizontal" method="post" name="add_employee" enctype="multipart/form-data" id="add_employee" action="<?php echo base_url('employee/updateemployee');?>" >
		<input type="hidden" name="empId" id="empId" value="<?php echo $getEmpDetails->empId; ?>" />
          <div class="form-group">
            <label class="control-label col-md-3">First Name: <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="fname" id="fname" placeholder="Enter First Name" value="<?php echo $name[0]; ?>">
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Last Name: <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" name="lname" id="lname" placeholder="Enter Last Name" value="<?php echo $name[1]; ?>">
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Username: <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" ng-model="username" name="username" id="username" placeholder="Enter Username" value="<?php echo $getEmpDetails->username; ?>">
			  <?php echo form_error('username'); ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Email: </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="text" name="email" id="email" placeholder="Enter Email" value="<?php echo $getEmpDetails->email; ?>">
			  <?php echo form_error('email'); ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Designation : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <select class="form-control" id="designation" name="designation">
                <option value="">Please choose designation</option>
				<option value="Admin" <?php if($getEmpDetails->designation == "Admin") echo 'selected="selected"'; ?>>Admin</option>
                <option value="Manager" <?php if($getEmpDetails->designation == "Manager") echo 'selected="selected"'; ?>>Manager</option>
                <option value="Sr.Architect" <?php if($getEmpDetails->designation == "Sr.Architect") echo 'selected="selected"'; ?>>Sr.Architect</option>
				<option value="jr.Architect" <?php if($getEmpDetails->designation == "jr.Architect") echo 'selected="selected"'; ?>>jr.Architect</option>
				<option value="Co-Ordinator" <?php if($getEmpDetails->designation == "Co-Ordinator") echo 'selected="selected"'; ?>>Co-Ordinator</option>
				<option value="Director" <?php if($getEmpDetails->designation == "Director") echo 'selected="selected"'; ?>>CEO & Managing Director</option>
				<option value="COO" <?php if($getEmpDetails->designation == "COO") echo 'selected="selected"'; ?>>COO</option>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">User Type : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <select class="form-control" id="user_type" name="user_type">
                   <option value="">Please choose Type</option>
                   <option value="admin" <?php if($getEmpDetails->user_type == "admin") echo 'selected="selected"'; ?>>Admin</option>
                   <option value="manager" <?php if($getEmpDetails->user_type == "manager") echo 'selected="selected"'; ?>>Manager</option>
                    <option value="developer" <?php if($getEmpDetails->user_type == "developer") echo 'selected="selected"'; ?>>Developer</option>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Image: </label>
            <div class="col-md-4">
              <input class="form-control col-md-8" type="file" name="employee_image" id="employee_image" placeholder="Upload Photo" onchange="PreviewImage();">
			  <br>
			  <div class="info"><img id="previewImg" class="user-img" width="120" height="120" src="<?php echo base_url().'uploads/employee_pic/'.$getEmpDetails->avatar; ?>"></div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('employee');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
            </div>
          </div>
        </form>
		
      </div>
    </div>
  </div>
 <?php endif; ?> 
</div>
<!-- Organizatoin form validation -->
<script type="text/javascript" language="javascript">
$(function() {
    $('#password').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
	 $('#username').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
});

		// Wait for the DOM to be ready
$(function() {
  $("form[name='add_employee']").validate({
    rules: {
      fname        		 : { required : true },
	  lname        		 : { required : true },
      username     		 : { required : true},
	 // email  	   		 : { required : true , email: true} ,
	  password  		 : { required : true } ,
	  designation  		 : { required : true } ,
	  user_type  		 : { required : true } ,
	},
    messages: {
     fname				 : "Please Enter First Name",
	 lname				 : "Please Enter Last Name",
	 username  			 : "Please Enter Username",	 
	// email				 : "Please Enter Email",
	 password  			 : "Please Enter Password",
	 designation  		 : "Please Select Designation",
	 user_type  		 : "Please Select User Type",
	 },					
     submitHandler: function(form) {
      form.submit();
    }
  });
});

/* employee profile picture preview images dispalying here */
 function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("employee_image").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("previewImg").src = oFREvent.target.result;
        };
    };

//Angular js with username same as password
var app = angular.module('empUsername', []);
app.controller('empCtrl', function($scope) {

<?php if(empty($getUpdateId)): ?> 
    
	$scope.username = "";
	
<?php else: ?>
	
	$scope.username = "<?php echo $getEmpDetails->username; ?>";

<?php endif; ?>	
});
//Angular Js with username same as password
</script>
<!-- Organizatoin form validation -->
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
