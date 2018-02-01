<!-- Inlude Header here -->
<?php $this->load->view('includes/cRMHeader'); ?>
<?php $getUpdateId = $this->uri->segment('3'); // Update Segment ?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Client Information</h1>
    </div>
  </div>
  <?php if(empty($getUpdateId)): ?>
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div>
          <h4 class="line-head">Add Client</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Clients" href="<?php echo base_url('clients');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
        <form class="form-horizontal" method="post" name="add_client" id="add_client" action="<?php echo base_url('clients/addclient');?>">
          <div class="form-group">
            <label class="control-label col-md-3">Client Name : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="client_name" id="client_name" placeholder="Enter Client Name" value="<?php echo set_value('client_name'); ?>">
              <?php echo form_error('client_name'); ?> </div>
          </div>		 
		  <div class="form-group">
            <label class="control-label col-md-3">Client Email : <!--<span class="required-star">*</span>--></label>
            <div class="col-md-4">
			  <input class="form-control" placeholder="Enter email address" type="email" name="client_email" id="client_email" value="<?php echo set_value('client_email'); ?>">
			   <?php echo form_error('client_email'); ?>
            </div>
		 </div>	
		  <div class="form-group">
            <label class="control-label col-md-3">Contact Number : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="client_contact_num" id="client_contact_num" placeholder="Enter Contact Number" value="<?php echo set_value('client_contact_num'); ?>">
             </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Description : <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <textarea class="form-control" name="client_desc" id="client_desc" placeholder="Enter Client Description" rows="3"><?php echo set_value('client_desc'); ?></textarea>
            </div>
          </div>		  
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('clients');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
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
          <h4 class="line-head">Update Client</h4>
          <span style="float:right; position:relative; top:-45px;"><a data-toggle="tooltip" title="Back To Clients" href="<?php echo base_url('clients');?>"><img src="<?php echo HTTP_IMAGES_PATH;?>new.png"></a> </span> </div>
        <div style="clear:both;"></div>
        
		<?php foreach($updateClient as $key => $getClient) { 	 }   ?>
		
        <form class="form-horizontal" method="post" name="add_client" id="add_client" action="<?php echo base_url('clients/updateclient');?>">
		<input type="hidden" name="client_Id" id="client_Id" value="<?php echo $getClient->client_Id; ?>" />
          <div class="form-group">
            <label class="control-label col-md-3">Client Name : <span class="required-star">*</span></label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="client_name" id="client_name" placeholder="Enter Client Name" value="<?php echo $getClient->client_name; ?>">
              <?php echo form_error('client_name'); ?> </div>
          </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Client Email : <!--<span class="required-star">*</span>--></label>
            <div class="col-md-4">
			  <input class="form-control" placeholder="Enter email address" type="email" name="client_email" id="client_email" value="<?php echo $getClient->client_email; ?>">
			  <?php echo form_error('client_email'); ?>
            </div>
		 </div>
		  <div class="form-group">
            <label class="control-label col-md-3">Contact Number : </label>
            <div class="col-md-4">
              <input class="form-control" type="text" name="client_contact_num" id="client_contact_num" placeholder="Enter Contact Number" value="<?php echo $getClient->client_contact_num; ?>">
             </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Description : <span class="required-star">*</span> </label>
            <div class="col-md-4">
              <textarea class="form-control" name="client_desc" id="client_desc" placeholder="Enter Client Description" rows="3"><?php echo $getClient->client_desc; ?></textarea>
            </div>
          </div>
		  
          <div class="card-footer">
            <div class="row">
              <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-primary icon-btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                   <a class="btn btn-default icon-btn" href="<?php echo base_url('clients');?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> </div>
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
      client_name        		 : { required : true },
	  //client_email        		 : { required : true },
	  client_desc        		 : { required : true },
     
	},
    messages: {
     client_name				 : "Please Enter Client Name",
	 //client_email				 : "Please Enter Client Email",
	 client_desc				 : "Please Enter Client Description",
	 },					
     submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>
<!-- Organizatoin form validation -->
<!-- Inlude Footer here -->
<?php $this->load->view('includes/cRMFooter'); ?>
<!-- Inlude Footer here END-->
