<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Manage Projects</h1>
    </div>
    <div><a class="btn btn-primary btn-flat" href="<?php echo base_url('projects/add'); ?>" data-toggle="tooltip" title="Add Project"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="Refresh" href="<?php echo base_url('projects'); ?>"><i class="fa fa-lg fa-refresh"></i></a></div>
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
                  <th>Project Name</th>
                  <th>Client Name</th>
				  <th>Created By</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  foreach ($getProjects as $key => $projectResult) :
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;				  
				  	 $createdExp 		= explode(" " , $projectResult->created_at);

					if($projectResult->status == 'Process'):

							$statusClass = 'class="label label-success"';
						
					 elseif($projectResult->status == 'Pending'):

							$statusClass = 'class="label label-warning"';				 
					 else:				 
							$statusClass = 'class="label label-danger"';
					 
					 endif;
     				 
				 ?>
                <tr <?php echo $showRowColour; ?> id="delProjectRow<?php echo $projectResult->project_Id; ?>">
                  <td><?php echo $i ?></td>
                  <td><?php echo ucfirst($projectResult->project_name);?> </td>
                  <td><?php echo ucfirst($projectResult->client_name);?> </td>
				  <td><span class="label label-info"><?php echo ucfirst($projectResult->name);?></span></td>
                  <td><a href="#"  data-toggle="tooltip" title="<?php echo $projectResult->project_desc;?>"><?php echo character_limiter($projectResult->project_desc, 30);?></a></td>
                  <td><span <?php echo $statusClass; ?>><?php echo $projectResult->status;?></span></td>
                  <th><?php echo date('d-M-Y',strtotime($createdExp[0]));?></th>
                  <th><a href="<?php echo base_url(); ?>projects/add/<?php echo $projectResult->project_Id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> | <a style="cursor:pointer;" data-toggle="tooltip" title="Delete" onClick="delete_project(<?php echo $projectResult->project_Id;?>)"><i class="fa fa-sm fa-trash"></i></a></th>
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
function delete_project(project_Id){ 
var answer = confirm ("Are you sure you want to delete project?");
if (answer) {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('projects/delete');?>",
                data: "project_Id="+project_Id,
				beforeSend: function() {
   							 $('#delProjectRow'+project_Id).html('<i class="fa fa-spinner"></i>');
 				 },success: function (response) { 	
					      
				       $("#delProjectRow"+project_Id).remove("#delProjectRow"+project_Id).html('');
			     }
            });
      }
}
</script>
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
