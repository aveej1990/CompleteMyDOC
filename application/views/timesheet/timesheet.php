<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->
<?php 

  $getClientNames      		= $this->client_model->getClientName(); // List of Clients
  
  $taskClientId   			=  '' ; // Getting client ID
  
  $getListOfProjects   		= $this->project_model->getProjectName($taskClientId); // List of Clients
  
  $getListOfEmployees   	= $this->timesheet_login->getEmployeeName(); // List of Clients
  
  $taskProjectId = '';
   
  $getListOfTask		   	= $this->task_model->getTaskName($taskProjectId); // List of Clients
		
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1><i class="fa fa-clock-o"></i>Timesheet</h1>
    </div>
	<div>
	     <a class="btn btn-info btn-flat" data-toggle="tooltip" title="Refresh" href="<?php echo base_url('timesheet');?>"><i class="fa fa-lg fa-refresh"></i></a></div>
     </div>
  <div class="card">
    <h3 class="card-title"></h3>
    <div class="card-body">
    <form class="" name="timesheet_search" id="timesheet_search"  method="post"  action="<?php base_url('timesheet'); ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Client's</label>
              <select class="form-control" id="client_Id" name="client_Id" onChange="searchProjects(this.value);">
                <option value="">Please select client</option>
                <option value="all">All</option>
                <?php foreach($getClientNames as $key => $clientName): ?>
                <option value="<?php echo $clientName->client_Id;?>"><?php echo ucfirst($clientName->client_name);?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Project's</label>
              <select class="form-control" id="project_Id" name="project_Id" onchange="searchProjectWiseTask(this.value)">
                <option value="">Please select project</option>
                <option value="all">All</option>
                <?php foreach($getListOfProjects as $key => $projectName): ?>
                <option value="<?php echo $projectName->project_Id;?>"><?php echo ucfirst($projectName->project_name);?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Task's</label>
              <select class="form-control" id="task_Id" name="task_Id">
                <option value="">Please select task</option>
                <option value="all">All</option>
                <?php foreach($getListOfTask as $key => $taskName): ?>
                <option value="<?php echo $taskName->task_Id;?>"><?php echo ucfirst($taskName->task_name);?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Employee's</label>
              <select class="form-control" id="empId" name="empId[]" multiple="multiple">
                <option value="all">All</option>
                <?php foreach($getListOfEmployees as $key => $employeeName): ?>
                <option value="<?php echo $employeeName->empId;?>"><?php echo ucfirst($employeeName->username);?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">From</label>
               <input class="form-control" type="text" id="form_date" name="form_date" placeholder="Select From Date" readonly="">
            </div>
          </div>
          <div class="col-md-6">
            <label class="control-label">To</label>
             <input class="form-control" type="text" id="to_date" name="to_date" placeholder="Select To Date" readonly="">
          </div>
        </div>
        <div class="card-footer">
                    <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                    <a  href="<?php echo base_url();?>empreports"  data-toggle="Go To Report Log!" title="Cancel">
                    <button class="btn btn-default icon-btn" type="button"><i class="fa fa-chevron-circle-left"></i>Back</button>
                    </a> </div>
	 </form>
    </div>
    
  </div>
  
  <?php if(count($getManageReportLog != 0)):?>
  <div class="card">
    <div class="card-body">
      <div class="row">
	     <?php if(!empty($getManageReportLog)): ?>
		 
		 <div align="center">  
		 <a href="<?php echo base_url()?>timesheet/excel?client_Id=<?php echo $_REQUEST['client_Id'];?>&project_Id=<?php echo $_REQUEST['project_Id'];?>&task_Id=<?php echo $_REQUEST['task_Id'];?>&empId=<?php echo implode(' ,' ,$_REQUEST['empId']);;?>&form_date=<?php echo $_REQUEST['form_date'];?>&to_date=<?php echo $_REQUEST['to_date'];?>">
				<button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Export To Excel Report</button>
			</a>
		 </div>
		 <?php endif; ?>
        <!-- Displaying Search Result -->
	    <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-hover table-bordered" id="organisationTable">
              <thead>
                <tr>
                  <th>Sno</th>
                  <th>Name</th>
				  <th>Client Name</th>
				  <th>Project Name</th>
				  <th>Task Name</th>
				  <th>Hours</th>
				  <th>Comments</th>
                  <th>Status</th>
                  <th>Date</th>                 
				</tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  $totalHours = 0;
				  if( !empty($getManageReportLog) ) : 
				  foreach ($getManageReportLog as $key => $reportResult) :
				  $totalHours += $reportResult->emp_time_hours; // Total Hours 
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;
				$getListOfProjects   	= $this->emptimelog_model->getAddedReportTaskNames($reportResult->task_Id); // List of tasks
				  ?>
                <tr <?php echo $showRowColour; ?> id="delRecordsRow<?php echo $reportResult->emp_record_id; ?>">
                  <td><?php echo $i ?></td>
                  <td nowrap="nowrap"><span class="label label-info"><?php echo ucfirst($reportResult->name);?></span></td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->client_name);?> </td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->project_name);?> </td>
				  <td nowrap="nowrap"><a href="#"  data-toggle="tooltip" title="<?php echo $getListOfProjects;?>"><?php echo character_limiter($getListOfProjects,20);?></a></td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->emp_time_hours);?> </td>
				  <td nowrap="nowrap"><a href="#"  data-toggle="tooltip" title="<?php echo $reportResult->comments;?>"><?php echo character_limiter($reportResult->comments, 20);?></a></td>
                  <td nowrap="nowrap"><span class="label label-success"><?php echo $reportResult->status;?></span></td>
                  <th nowrap="nowrap"><?php echo date('d-M-Y',strtotime($reportResult->emp_report_dates));?></th>
                 </tr>
                <?php $i++; endforeach;  endif; ?>
				<?php if($getManageReportLog > 1): ?>
					<span style="position:absolute; margin-left:60%; margin-top:-2%;"><?php  echo 'Total Hours : <b style="color: #1322d2; font-size:20px;">'.$totalHours.'</b>'; ?></span>
				<?php endif; ?>
              </tbody>
            </table>
        </div>
        </div>
	  <!-- Displaying Search Result -->  
      </div>	  
    </div>
  </div>
  
   <?php endif; ?>	
   
   
</div>
<script language="javascript" type="text/javascript">

$(function() {
  $("form[name='timesheet_search']").validate({
    rules: {
      client_Id        			 : { required : true },
	  project_Id        		 : { required : true },
	  task_Id        			 : { required : true },
	  "empId[]"	        		 : { required : true },
	  form_date        	 		 : { required : true },
	  to_date					 : { required : true }
	 },
    messages: {
     client_Id				     : "Please Select Client Name",
	 project_Id				     : "Please Select Project Name",
	 task_Id				     : "Please Select Task Name",
	 "empId[]"				     : "Please Select Project Name",
	 form_date					 : "Please Select From Date",
	 to_date					 : "Please Select To Date"
	 },					
     submitHandler: function(form) {
      form.submit();
    }
  });
});


function searchProjects(client_Id) {   // Getting client wise projects based on client id
	$.ajax({
	type: "POST",
	url: "<?php echo base_url('empreports/getClientProjects');?>",
	data:'client_Id='+client_Id,
	success: function(data){
		$("#project_Id").html(data);
		$('#project_Id').removeAttr('disabled');
	}
});
}

function searchProjectWiseTask(project_Id) {   // Getting projects wise task based on project id
	$.ajax({
	type: "POST",
	url: "<?php echo base_url('empreports/searchProjectsTask');?>",
	data:'project_Id='+project_Id,
	success: function(data){
		$("#task_Id").html(data);
		$('#task_Id').removeAttr('disabled');
	}
});
}

 $(document).ready(function () {
       var today = $("#form_date").val();
		$("#form_date, #to_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1,
            onSelect: function (selectedDate) {
                if (this.id == 'form_date') {
                    var dateMin = $('#form_date').datepicker("getDate");
                    //var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
					var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate());
                    var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 365);
                    $('#to_date').datepicker("option", "minDate", rMin);
                    $('#to_date').datepicker("option", "maxDate", rMax);
                }
               

            }
        });
        $('#to_date').datepicker("option", "minDate", new Date(today));

    })


$('#client_Id,#project_Id,#empId,#task_Id').select2();	 // Autosuggest list

//generate employee excel report 

function generateEmployeeExcelReport(){
	
	alert('Hello');
}

</script>
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
