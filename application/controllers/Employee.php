<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct() {
		
		parent::__construct();
		// Load form helper library
		$this->load->helper('form');
		// Load form validation library
		$this->load->library('form_validation');
		// Load session library
		$this->load->library('session');	
		// Load database
		 
		$this->load->model('timesheet_login');
		
		if(empty($this->session->userdata['logged_in_timesheet'])){
		
			redirect('home/login');
		}
		
    }
	
	public function index(){
	
			$data['getEmployees'] = $this->timesheet_login->getEmployees();
			
			$this->load->view('employee/employees' , $data);
	}
	
	public function add($empId = NULL){
	
	   if(empty($empId)) : 
			
			 $this->load->view('employee/add_employees');
			 
		else:
			
			   $data['updateEmployee'] = $this->timesheet_login->getEmployees($empId);
	
		    	$this->load->view('employee/add_employees' , $data);
					
		endif;	   
			
	 
	}
	public function dontadd()
	{
		 
	   if(empty($empId) && $empId1=='') {
			
			 $this->load->view('employee/add_employees');
	   }
			 
		else
		{
			
			   $data['updateEmployee'] = $this->timesheet_login->getEmployees($empId);
	
		    	$this->load->view('employee/add_employees' , $data);
		}
					
	    
			
	}
	
	public function addEmployee(){
	
	     // Adding new organization function. 
		
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('username', 'Username alreddy exit. Please try another Username', 'required|trim|is_unique[employee_details.username]');
		
		//$this->form_validation->set_rules('email', 'Email Id alreddy exit. Please try another Email Id', 'required|trim|is_unique[employee_details.email]');
		
		if ($this->form_validation->run() == FALSE) {
	
			$this->load->view('employee/add_employees');
			
	    }else{
				
			
			/* User profile picture uploding functionality  */
		
			$config['upload_path'] = 'uploads/employee_pic/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = $empId.'_'.$_FILES['employee_image']['name'];
			$config['overwrite']     = false;
			$config['max_size']	 = '5120';
			 //$this->upload->initialize($config);
			 $this->load->library('upload', $config);
		   //Load upload library and initialize configuration
					if($this->upload->do_upload('employee_image')){
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
					}else{
						 $picture = 'default.jpg';
					}
		/* User profile picture uploding functionality  */		
		
		
		  
		
			
			$data = array(
			'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
			'email' 			 => $this->input->post('email'),
			'username' 			 => strtolower($this->input->post('username')),
			'password' 			 => md5(strtolower($this->input->post('password'))),
			'designation'		 => $this->input->post('designation'),
			'user_type'		 	 => $this->input->post('user_type'),
			'avatar' 			 => $picture,
			'created_at'    	 => date('Y-m-d H:i:s'),
			'updated_at' 		 => date('Y-m-d H:i:s')
			);
	
	     $this->timesheet_login->add_employee($data);
		
		 redirect('employee');
		
	   }
	
	}
	
	/*public function updateemployee(){  // Commented an 24-07-2016
	
	  
	    // Adding new organization function.
		
		$empId  =  $this->input->post('empId');
		
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('username', 'Username alreddy exit. Please try another Username', 'required|trim|is_unique[employee_details.username]');
		
		$this->form_validation->set_rules('email', 'Email Id alreddy exit. Please try another Email Id', 'required|trim|is_unique[employee_details.email]');
		
		if ($this->form_validation->run() == FALSE) {
		
		    $data['updateEmployee'] = $this->timesheet_login->getEmployees($empId);
	
			$this->load->view('employee/add_employees' , $data);
			
			
	    }else{
				
			$data = array(
			'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
			'email' 			 => $this->input->post('email'),
			'username' 			 => strtolower($this->input->post('username')),
			'password' 			 => md5($this->input->post('password')),
			'designation'		 => $this->input->post('designation'),
			'user_type'		 => $this->input->post('user_type'),
			'avatar' 			 => 'default.jpg',
			'updated_at' 		 => date('Y-m-d H:i:s')
			);
	
	     $this->timesheet_login->update_employee($data , $empId);
		
		 redirect('employee');
		
		// }
	
	} */
	
	
	public function updateemployee(){
		
	    // Adding new organization function.
		
		$update_empId  = $this->input->post('update_empId'); // Update profile user id 		
		
		if(empty($update_empId)) { // this condition based on update profile for particular manager or developer 
		
		$empId  	 =  $this->input->post('empId');
		
		$username 	 = strtolower($this->input->post('username'));
		
		$userQ = $this->db->get_where('employee_details',array('username'=>$username));		
		
		$countUsers = $userQ->num_rows(); 
		
		
		/* User profile picture uploding functionality  */
		
			$config['upload_path'] = 'uploads/employee_pic/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = $empId.'_'.$_FILES['employee_image']['name'];
			$config['overwrite']     = false;
			$config['max_size']	 = '5120';
			 //$this->upload->initialize($config);
			 $this->load->library('upload', $config);
		   //Load upload library and initialize configuration
					if($this->upload->do_upload('employee_image')){
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
					}
		/* User profile picture uploding functionality  */		
		
		
		
		if($countUsers == 0 ){		
			
		if(!empty($picture)) {
			
			$data = array(
					'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
					'email' 			 => $this->input->post('email'),
					'username' 			 => strtolower($this->input->post('username')),
					'designation'		 => $this->input->post('designation'),
					'user_type'		     => $this->input->post('user_type'),
					'avatar' 			 => $picture,
					'updated_at' 		 => date('Y-m-d H:i:s')
			);

		}else{
		
				$data = array(
					'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
					'email' 			 => $this->input->post('email'),
					'username' 			 => strtolower($this->input->post('username')),
					'designation'		 => $this->input->post('designation'),
					'user_type'		     => $this->input->post('user_type'),
					'updated_at' 		 => date('Y-m-d H:i:s')
			     );
		
		}			
	 
	     $this->timesheet_login->update_employee($data , $empId);
		
		 echo  '<div style="color:#FF0000; font-size:16px; padding:30px;"> Employee details successfully updated. </div>';
	       
		 echo  '<script>setTimeout(function(){window.location.href="'.base_url('employee').'"},3000);</script>'; 
		
		}else{
			
			if(!empty($picture)) {
				
				$data = array(
							'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
							'email' 			 => $this->input->post('email'),
							'designation'		 => $this->input->post('designation'),
							'user_type'		     => $this->input->post('user_type'),
							'avatar' 			 => $picture,
							'updated_at' 		 => date('Y-m-d H:i:s')
			     );
				
			}else{
				
				$data = array(
							'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
							'email' 			 => $this->input->post('email'),
							'designation'		 => $this->input->post('designation'),
							'user_type'		     => $this->input->post('user_type'),
							'updated_at' 		 => date('Y-m-d H:i:s')
			     );
				
			}			
			
			
	 
	        $this->timesheet_login->update_employee($data , $empId);
			 
			 echo  '<div style="color:#FF0000; font-size:16px; padding:30px;"> Username Already exist in database.</div>';
	       
		     echo  '<script>setTimeout(function(){window.location.href="'.base_url('employee').'"},3000);</script>'; 
		
		}
		
		}else{ 

          
		/* User profile picture uploding functionality  */
		
			$config['upload_path'] = 'uploads/employee_pic/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = $update_empId.'_'.$_FILES['employee_image']['name'];
			$config['overwrite']     = false;
			$config['max_size']	 = '5120';
			 //$this->upload->initialize($config);
			 $this->load->library('upload', $config);
		   //Load upload library and initialize configuration
					if($this->upload->do_upload('employee_image')){
						$uploadData = $this->upload->data();
						$picture = $uploadData['file_name'];
					}
		/* User profile picture uploding functionality  */		
		  
		if(!empty($picture)) {
		
			$data = array(
				'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
				'avatar' 			 => $picture,
				'mobile_num'         => $this->input->post('mobile_num'),
				'updated_at' 		 => date('Y-m-d H:i:s')
			 );
	 
		}else{
			
				$data = array(
				'name' 				 => $this->input->post('fname').' '.$this->input->post('lname'),
				'mobile_num'         => $this->input->post('mobile_num'),
			);
			
		}
	 
	     $this->timesheet_login->update_employee($data , $update_empId);
		
		 $this->session->set_flashdata('msg', 'Your profile details successfully updated!</span>');
              
		 redirect(base_url().'empreports/cPass');
		
		
        }		
		
	
	}
	
	
	/* public function delete(){  // Delete employee single record into databasse
	
		   $empId  = $this->input->post('empId');
		   
			if(!empty($empId)):
			
				$del = $this->timesheet_login->del_employee($empId);
				
			endif;	
	}*/
	
	public function update_emp_status(){  // Delete employee single record into databasse
	
		   $empId 	 = $this->input->post('empId');
		   $status 	 = $this->input->post('status');
		   
			if(!empty($empId)):
			
				$del = $this->timesheet_login->update_employee_status($empId , $status);
				
			endif;	
	}
	
	
	 public function getRecentEmployees(){  //Get Recent Employee Information
  
    	$recentEmp = $this->timesheet_login->recentEmployees();
		
		echo json_encode($recentEmp);
  
  }
  
  
  public function cPass(){  //Change Password
   
   		$empId  = $this->uri->segment(3); 

   		$pass=$this->input->post('password');
		
		if($pass!=='')
		{
		
			$password		 = 	$this->input->post('password');
			
			$this->timesheet_login->updateChangePassword( $password , $empId );
			
			 $this->session->set_flashdata('msg', 'Your Password Successfully Changed!</span>');
              
			 redirect(base_url().'employee/cPass/'.$empId );
			
		}
		else
		{
		
			$this->load->view('employee/changepassword');
		}
			
		 
		
   
   }	
	
	
}

