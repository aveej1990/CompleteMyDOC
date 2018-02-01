<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Report Log</h1>
    </div>
    <div>
	 <?php if($this->session->userdata['logged_in_timesheet']['user_type'] == 'developer' || $this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'): ?>
		<a class="btn btn-primary btn-flat" href="<?php echo base_url();?>empreports/add" data-toggle="tooltip" title="Add"><i class="fa fa-lg fa-plus"></i></a>
	  <?php endif; ?>
	<a class="btn btn-info btn-flat" data-toggle="tooltip" title="Refresh" href="<?php echo base_url();?>empreports"><i class="fa fa-lg fa-refresh"></i></a></div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
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
				  foreach ($getRecords as $key => $reportResult) :
				  $totalHours += $reportResult->emp_time_hours; // Total Hours 
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;
					$getListOfProjects   	= $this->emptimelog_model->getAddedReportTaskNames($reportResult->task_Id); // List of tasks					 
				  ?>
                <tr <?php echo $showRowColour; ?> id="delRecordsRow<?php echo $reportResult->emp_record_id; ?>">
                  <td><?php echo $i ?></td>
                  <td><span class="label label-info"><?php echo ucfirst($reportResult->name);?></span> </td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->client_name);?> </td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->project_name);?> </td>
				  <td nowrap="nowrap"><a href="#"  data-toggle="tooltip" title="<?php echo $getListOfProjects;?>"><?php echo character_limiter($getListOfProjects,20);?></a></td>
				  <td nowrap="nowrap"><?php echo ucfirst($reportResult->emp_time_hours);?> </td>
				  <td nowrap="nowrap"><a href="#"  data-toggle="tooltip" title="<?php echo $reportResult->comments;?>"><?php echo character_limiter($reportResult->comments, 20);?></a></td>
                  
				  <td nowrap="nowrap">
				 <?php if($this->session->userdata['logged_in_timesheet']['user_type']== 'manager' || $this->session->userdata['logged_in_timesheet']['user_type'] == 'admin'): ?> 
				  <span id="changeStatusRow_<?php echo $reportResult->emp_record_id; ?>"><a class="<?php echo ($reportResult->status=='Approved')? 'fa fa-check-circle label label-success' : 'fa fa-ban label label-danger'?>" style="cursor:pointer;" data-toggle="tooltip" title="Click To <?php echo ($reportResult->status=='Approved')? 'Unapproved' : 'Approved'?>" onClick="update_emp_report_status(<?php echo $reportResult->emp_record_id;?>,'<?php echo $reportResult->status; ?>')"> <?php echo $reportResult->status;?></a></span>
				 <?php else: ?>
				 <span class="<?php echo ($reportResult->status=='Approved')? 'fa fa-check-circle label label-success' : 'fa fa-ban label label-danger'?>"> <?php echo $reportResult->status;?></span>
				 <?php endif; ?> 
				  </td>
                  <th nowrap="nowrap"><?php echo date('d-M-Y',strtotime($reportResult->emp_report_dates));?></th>
                 <?php if($reportResult->empId == $this->session->userdata['logged_in_timesheet']['empId'] && $reportResult->status !='Approved'): ?>
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
      </div>
    </div>
  </div>
</div>
<!-- Inlude Footer here -->
<script type="text/javascript">
function delete_emp_record(emp_record_id){ 
var answer = confirm ("Are you sure you want to delete record?");
if (answer) {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('empreports/delete');?>",
                data: "emp_record_id="+emp_record_id,
				beforeSend: function() {
   							 $('#delRecordsRow'+emp_record_id).html('<i class="fa fa-spinner"></i>');
 				 },success: function (response) { 	
					      
				       $("#delRecordsRow"+emp_record_id).remove("#delRecordsRow"+emp_record_id).html('');
			     }
            });
      }
}
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
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
