<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Task List</h1>
    </div>
    <div><a class="btn btn-primary btn-flat" href="<?php echo base_url('task/add'); ?>" data-toggle="tooltip" title="Add Task"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="Refresh" href="<?php echo base_url('task'); ?>"><i class="fa fa-lg fa-refresh"></i></a></div>
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
                  <th>Task Name</th>
				  <th>Project Name</th>
                  <th>Client Name</th>
				  <th>Created By</th>
				  <th>Status</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  foreach ($getTaskList as $key => $taskResult) :
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;				  
				  	 $createdExp 		= explode(" " , $taskResult->created_at);	
					 
					 if($taskResult->status == 'Process'):

							$statusClass = 'class="label label-success"';
						
					 elseif($taskResult->status == 'Pending'):

							$statusClass = 'class="label label-warning"';				 
					 else:				 
							$statusClass = 'class="label label-danger"';
					 
					 endif;
				 ?>
                <tr <?php echo $showRowColour; ?> id="delTaskRow<?php echo $taskResult->task_Id; ?>">
                  <td><?php echo $i ?></td>
                  <td><?php echo ucfirst($taskResult->task_name);?> </td>
				  <td><?php echo ucfirst($taskResult->project_name);?> </td>
				  <td><?php echo ucfirst($taskResult->client_name);?> </td>
				  <td><span class="label label-info"><?php echo ucfirst($taskResult->name);?></span></td>
				  <td><span <?php echo $statusClass; ?>><?php echo $taskResult->status;?></span></td>
                  <th><?php echo date('d-M-Y',strtotime($createdExp[0]));?></th>
                  <th><a href="<?php echo base_url(); ?>task/add/<?php echo $taskResult->task_Id; ?>/<?php echo $taskResult->client_Id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> | <a style="cursor:pointer;" data-toggle="tooltip" title="Delete" onClick="delete_project(<?php echo $taskResult->task_Id;?>)"><i class="fa fa-sm fa-trash"></i></a></th>
				 </tr>
                <?php $i++; endforeach; ?>
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
function delete_project(task_Id){ 
var answer = confirm ("Are you sure you want to delete task?");
if (answer) {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('task/delete');?>",
                data: "task_Id="+task_Id,
				beforeSend: function() {
   							 $('#delTaskRow'+task_Id).html('<i class="fa fa-spinner"></i>');
 				 },success: function (response) { 	
					      
				       $("#delTaskRow"+task_Id).remove("#delTaskRow"+task_Id).html('');
			     }
            });
      }
}
</script>
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
