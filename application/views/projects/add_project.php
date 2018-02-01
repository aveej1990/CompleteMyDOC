<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<?php 

  $getUpdateId = $this->uri->segment('3'); // Update Segment 
  
  $getClientNames = $this->client_model->getClientName(); // List of Clients
  
  
		
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Project Information</h1>
    </div>
  </div>
  <?php if(empty($getUpdateId)): ?>
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div>
          <h4 class="line-head">Add Project</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Projects" href="<?php echo base_url('projects');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
        <form class="form-horizontal" method="post" name="add_client" id="add_client" action="<?php echo base_url('projects/addproject');?>">
          
		  <div class="form-group">
            <label class="control-label col-md-3"> Client : <span class="required-star">*</span></label>
            <div class="col-md-4">
             <select class="form-control" id="client_Id" name="client_Id">
                <option value="">Please select client</option>
                <?php foreach($getClientNames as $key => $clientName): ?>
				<option value="<?php echo $clientName->client_Id;?>"><?php echo ucfirst($clientName->client_name);?></option>
				<?php endforeach; ?>
             </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Name : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_name" id="project_name" placeholder="Enter Project Name" value="<?php echo set_value('project_name'); ?>">
              <?php echo form_error('project_name'); ?> </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Description : <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <textarea class="form-control" name="project_desc" id="project_desc" placeholder="Enter Project Description" rows="3"><?php echo set_value('project_desc'); ?></textarea>
            </div>
          </div>
		   <div class="form-group">
            <label class="control-label col-md-3">Amount : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_cost" id="project_cost" placeholder="Enter Project Amount" value="<?php echo set_value('project_cost'); ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Start Date : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_start_date" id="project_start_date" readonly="" placeholder="Enter Project Start Date" value="<?php echo set_value('project_start_date'); ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">End Date : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_end_date" id="project_end_date" readonly="" placeholder="Enter Project End Date" value="<?php echo set_value('project_end_date'); ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Status : <span class="required-star">*</span></label>
            <div class="col-md-4">
             <select class="form-control" id="status" name="status">
                <option value="">Please select status</option>
                <option value="Process">Process</option>
				<option value="Pending">Pending</option>
				<option value="Closed">Closed</option>
             </select>
            </div>
          </div>
		  
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('projects');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
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
          <h4 class="line-head">Update Project</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Projects" href="<?php echo base_url('projects');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
        
		<?php foreach($updateProject as $key => $getProjectData) { 	 }   ?>
		
		<form class="form-horizontal" method="post" name="add_client" id="add_client" action="<?php echo base_url('projects/updateproject');?>">
         <input type="hidden" id="project_id" name="project_id" value="<?php echo $getProjectData->project_Id; ?>" /> 
		  <div class="form-group">
            <label class="control-label col-md-3"> Client : <span class="required-star">*</span></label>
            <div class="col-md-4">
             <select class="form-control" id="client_Id" name="client_Id">
                <option value="">Please select client</option>
                <?php foreach($getClientNames as $key => $clientName): ?>
				<option value="<?php echo $clientName->client_Id;?>" <?php if($getProjectData->client_Id == $clientName->client_Id) echo 'selected="selected"'; ?>><?php echo ucfirst($clientName->client_name);?></option>
				<?php endforeach; ?>
             </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Name : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_name" id="project_name" placeholder="Enter Project Name" value="<?php echo $getProjectData->project_name; ?>">
              <?php echo form_error('project_name'); ?> </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Description : <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <textarea class="form-control" name="project_desc" id="project_desc" placeholder="Enter Project Description" rows="3"><?php echo $getProjectData->project_desc; ?></textarea>
            </div>
          </div>
		   <div class="form-group">
            <label class="control-label col-md-3">Amount : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_cost" id="project_cost" placeholder="Enter Project Amount" value="<?php echo $getProjectData->project_cost; ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Start Date : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_start_date" id="project_start_date" readonly="" placeholder="Enter Project Start Date" value="<?php echo $getProjectData->project_start_date; ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">End Date : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="project_end_date" id="project_end_date" readonly="" placeholder="Enter Project End Date" value="<?php echo $getProjectData->project_end_date; ?>">
             </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Status : <span class="required-star">*</span></label>
            <div class="col-md-4">
             <select class="form-control" id="status" name="status">
                <option value="">Please select status</option>
                <option value="Process" <?php if($getProjectData->status == "Process") echo 'selected="selected"'; ?>>Process</option>
				<option value="Pending" <?php if($getProjectData->status == "Pending") echo 'selected="selected"'; ?>>Pending</option>
				<option value="Closed" <?php if($getProjectData->status == "Closed") echo 'selected="selected"'; ?>>Closed</option>
             </select>
            </div>
          </div>
		  
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('projects');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
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
// Wait for the DOM to be ready
$(function() {
  $("form[name='add_client']").validate({
    rules: {
      client_Id        			 : { required : true },
	  project_name        		 : { required : true },
	  project_desc        		 : { required : true },
	  status        		 	 : { required : true },
     
	},
    messages: {
     client_Id				     : "Please Select Client Name",
	 project_name				 : "Please Enter Project Name",
	 project_desc				 : "Please Enter Project Description",
	 status				 		 : "Please Select Project Status",
	 },					
     submitHandler: function(form) {
      form.submit();
    }
  });
});

 $(document).ready(function () {
       var today = $("#project_start_date").val();
		$("#project_start_date, #project_end_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1,
            onSelect: function (selectedDate) {
                if (this.id == 'project_start_date') {
                    var dateMin = $('#project_start_date').datepicker("getDate");
                    //var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
					var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate());
                    var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 365);
                    $('#project_end_date').datepicker("option", "minDate", rMin);
                    $('#project_end_date').datepicker("option", "maxDate", rMax);
                }
               

            }
        });
        $('#project_end_date').datepicker("option", "minDate", new Date(today));

    })
$('#client_Id').select2();	 // Autosuggest list on clients
</script>
<!-- Organizatoin form validation -->
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
