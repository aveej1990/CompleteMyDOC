<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<?php 

  $getClientNames      		= $this->client_model->getClientName(); // List of Clients
  
   $taskClientId   			= ''; // Getting client ID
  
   $getListOfProjects   		= $this->project_model->getProjectName($taskClientId); // List of Clients
  
 // $getListOfEmployees   	= $this->timesheet_login->getEmployeeName(); // List of Clients
  
   $taskProjectId = '';
  
   $getListOfTask		   	= $this->task_model->getTaskName($taskProjectId); // List of Clients
 
 		
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1><i class="fa fa-clock-o"></i> Search Employee Reports </h1>
    </div>
    <div> <a class="btn btn-primary btn-flat" href="<?php echo base_url();?>empreports"  data-toggle="tooltip" title="Go To Report Log!"><i class="fa fa-chevron-circle-left"></i></a> </div>
  </div>
  <div class="card">
    <h3 class="card-title"></h3>
    <div class="card-body">
      <div class="row">
       <!-- Search for employee with date wise and client , project wise as well. -->
	    <div class="col-md-12">
          <div class="bs-component">
            <div class="tab-content" id="myTabContent">
              <!-- Employee Report adding block -->
              <form class="" name="emp_search_log" id="emp_search_log"  method="post"  action="<?php echo base_url('empreports/searchreportlog');?>">
                <div class="tab-pane fade active in" id="Add">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Client's</label>
                        <select class="form-control" id="client_Id" name="client_Id" onChange="getProjects(this.value);">
                          <option value="">Please select client</option>
						  <option value="all">All</option>
						  <?php foreach($getClientNames as $key => $clientName): ?>
                          <option value="<?php echo $clientName->client_Id;?>"><?php echo ucfirst($clientName->client_name);?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Project's</label>
                        <select class="form-control" id="project_Id" name="project_Id">
						  <option value="">Please select project</option>
						  <?php foreach($getListOfProjects as $key => $projectName): ?>
                          <option value="<?php echo $projectName->project_Id;?>" ><?php echo ucfirst($projectName->project_name);?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">From Date</label>
                        <input class="form-control" type="text" id="form_date" name="form_date" placeholder="Select From Date" readonly="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">To Date</label>
                        <input class="form-control" type="text" id="to_date" name="to_date" placeholder="Select To Date" readonly="">
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                    <a  href="<?php echo base_url();?>empreports"  data-toggle="Go To Report Log!" title="Cancel">
                    <button class="btn btn-default icon-btn" type="button"><i class="fa fa-chevron-circle-left"></i>Back</button>
                    </a> </div>
                </div>
              </form>
              <!-- Employee Report adding block -->
            </div>
          </div>
        </div>
	   <!--Search for employee with date wise and client , project wise as well.  -->
      </div>	  
    </div>
	
  </div>
  <?php if(!empty($resultTimeLog)):?>
  <div class="card">
    <div class="card-body">
      <div class="row">
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
				  <th>Action</th>				 
                </tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  $totalHours = 0;
				  foreach ($resultTimeLog as $key => $reportResult) :
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
                 <td nowrap="nowrap">
				 <?php if($this->session->userdata['logged_in_timesheet']['user_type']== 'manager' || $this->session->userdata['logged_in_timesheet']['user_type'] == 'admin'): ?> 
				  <span id="changeStatusRow_<?php echo $reportResult->emp_record_id; ?>"><a class="<?php echo ($reportResult->status=='Approved')? 'fa fa-check-circle label label-success' : 'fa fa-ban label label-danger'?>" style="cursor:pointer;" data-toggle="tooltip" title="Click To <?php echo ($reportResult->status=='Approved')? 'Unapproved' : 'Approved'?>" onClick="update_emp_report_status(<?php echo $reportResult->emp_record_id;?>,'<?php echo $reportResult->status;; ?>')"> <?php echo $reportResult->status;?></a></span>
				 <?php else: ?>
				 <span class="<?php echo ($reportResult->status=='Approved')? 'fa fa-check-circle label label-success' : 'fa fa-ban label label-danger'?>"> <?php echo $reportResult->status;?></span>
				 <?php endif; ?> 
				  </td>
                  
				  
				  <th nowrap="nowrap"><?php echo date('d-M-Y',strtotime($reportResult->emp_report_dates));?></th>
				  
				  
                 <?php if($this->session->userdata['logged_in_timesheet']['empId'] == $reportResult->empId && $reportResult->status !='Approved'): ?>
				  <th nowrap="nowrap"><a href="#" data-toggle="tooltip" title="View"><i class="fa fa-history" aria-hidden="true"></i></a> | <a href="<?php echo base_url(); ?>empreports/add/<?php echo $reportResult->emp_record_id; ?>/<?php echo $reportResult->project_Id; ?>/<?php echo $reportResult->client_Id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> | <a style="cursor:pointer;" data-toggle="tooltip" title="Delete" onClick="delete_emp_record(<?php echo $reportResult->emp_record_id;?>)"><i class="fa fa-sm fa-trash"></i></a></th>
				  <?php else: ?>
				  <th nowrap="nowrap" style="text-align:center"><a href="#" data-toggle="tooltip" title="View"><i class="fa fa-history" aria-hidden="true"></i></a></th>
				  <?php endif; ?>
				 </tr>
                <?php $i++; endforeach; ?>
				<div align="center"><?php  echo 'Total Hours : <b style="color: #1322d2; font-size:20px;">'.$totalHours.'</b>'; ?></div>
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
/* DatePicker */
$(function() {
  $("form[name='emp_search_log']").validate({
    rules: {
      client_Id        			 : { required : true },
	  project_Id        		 : { required : true },
	  form_date        	 		 : { required : true },
	  to_date					 : { required : true }
	 },
    messages: {
     client_Id				     : "Please Select Client Name",
	 project_Id				     : "Please Select Project Name",
	 form_date					 : "Please Select From Date",
	 to_date					 : "Please Select To Date"
	 },					
     submitHandler: function(form) {
      form.submit();
    }
  });
});

/*Ajax Based dropdown option changes on Clients , Projects and Tasks*/

function getProjects(client_Id) {   // Getting client wise projects based on client id
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

$('#client_Id,#project_Id').select2();	 // Autosuggest list


</script>
<script type="text/javascript">
var status;
function update_emp_report_status(emp_record_id,status){   
var updateStatus = (status=='Approved')? 'Unapproved' : 'Approved';
var answer = confirm ("Are you sure you want to "+updateStatus+" report log ");
//alert(updateStatus); 
if (answer) {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('empreports/update_emp_report_status');?>",
                data: "emp_record_id="+emp_record_id+'&status='+updateStatus,
				beforeSend: function() {
   							$('#changeStatusRow_'+emp_record_id).html('<i class="fa fa-spinner"></i>');
 				 },success: function (response) {  //alert('---' + response)
				            $("#changeStatusRow_"+emp_record_id).html(response);
							//location.reload();
			     }
            });
      }
}
</script>
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
