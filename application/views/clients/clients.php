<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<!-- Inlude Header here END-->

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Manage Clients</h1>
    </div>
    <div><a class="btn btn-primary btn-flat" href="<?php echo base_url('clients/add'); ?>" data-toggle="tooltip" title="Add Client"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="Refresh" href="<?php echo base_url('clients'); ?>"><i class="fa fa-lg fa-refresh"></i></a></div>
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
                  <th>Client Name</th>
				   <th>Client Email</th>
                  <th>Created By</th>
                  <th>Description</th>
				  <th>Contact Num</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				  $i=1;
				  foreach ($getClients as $key => $clientResult) :
				 	 if($i%2 == 0): $showRowColour = 'class="success"'; else: $showRowColour = 'class="info"'; endif;				  
				  	 $createdExp 		= explode(" " , $clientResult->created_at);	
				 ?>
                <tr <?php echo $showRowColour; ?> id="delClientRow<?php echo $clientResult->client_Id; ?>">
                  <td><?php echo $i ?></td>
                  <td><?php echo ucwords($clientResult->client_name);?></td>
				   <td><?php echo $clientResult->client_email;?></td>
                  <td><span class="label label-info"><?php echo ucfirst($clientResult->name);?></span></td>
                  <td><a href="#"  data-toggle="tooltip" title="<?php echo $clientResult->client_desc;?>"><?php echo character_limiter($clientResult->client_desc, 20);?></a></td>
				  <td><?php echo $clientResult->client_contact_num;?></td>
                  <td><span class="label label-success"><?php echo $clientResult->status;?></span></td>
                  <th><?php echo date('d-M-Y',strtotime($createdExp[0]));?></th>
                  <th><a href="<?php echo base_url(); ?>clients/add/<?php echo $clientResult->client_Id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> | <a style="cursor:pointer;" data-toggle="tooltip" title="Delete" onClick="delete_client(<?php echo $clientResult->client_Id;?>)"><i class="fa fa-sm fa-trash"></i></a></th>
				  
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
function delete_client(client_Id){ 
var answer = confirm ("Are you sure you want to delete client?");
if (answer) {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('clients/delete');?>",
                data: "client_Id="+client_Id,
				beforeSend: function() {
   							 $('#delClientRow'+client_Id).html('<i class="fa fa-spinner"></i>');
 				 },success: function (response) { 
				       $("#delClientRow"+client_Id).remove("#delClientRow"+client_Id).html('');
			     }
            });
      }
}
</script>
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
