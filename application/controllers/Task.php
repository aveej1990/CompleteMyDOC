<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

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
		
		$this->load->helper('text');
		
		// Load database		
		$this->load->model('timesheet_login');
		
		$this->load->model('client_model');
		
		$this->load->model('project_model');
		
		$this->load->model('task_model');
		
		if(empty($this->session->userdata['logged_in_timesheet'])){
		
			redirect('home/login');
		}
		
    }
	
	public function index(){
		
			$data['getTaskList'] = $this->task_model->getTaskList();
			
			$this->load->view('task/task_list' , $data);
			
	}
	
	public function add($task_Id = NULL){
	
	   if(empty($task_Id)) : 
			
			 $this->load->view('task/add_task');
			 
		else:
			
			 $data['updateTask'] = $this->task_model->getTaskList($task_Id);
	
		     $this->load->view('task/add_task' , $data);
					
		endif;	   
			
	 
	}
	
	public function addTask(){ // Adding new Client function. 	
	
	
			
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	     $this->form_validation->set_rules('task_name', 'Task name already exit. Please try another task name', 'required|trim|callback_exists_tasks');
		
		if ($this->form_validation->run() == FALSE) {
	
			$this->load->view('task/add_task');
			
	    }else{
						
			$data = array(
			'client_Id' 				 => $this->input->post('client_Id'),
			'project_Id' 				 => $this->input->post('project_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			//'empId'						 => implode(',',$this->input->post('empId')), // Store employees with comma separate 
			'task_name' 				 => $this->input->post('task_name'),
			'task_desc' 				 => $this->input->post('task_desc'),
			'status'				 	 => $this->input->post('status'),
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->task_model->add_task($data);
		
		 redirect('task');
		
	   }
	
	}
	
	
	public function updatetask(){ // Adding new Client function. 	
			
		
		$task_Id = $this->input->post('task_Id');
		
	 	$taskAREQ  = $this->db->select('*')->from('task_details')
									   ->where('client_Id' , $this->input->post('client_Id'))
									   ->where('project_Id' , $this->input->post('project_Id'))
									   ->where('task_name' , $this->input->post('task_name'))
									   ->get();
	  
	  if ($taskAREQ->num_rows() == 0 ) {
	
			$data = array(
			'client_Id' 				 => $this->input->post('client_Id'),
			'project_Id' 				 => $this->input->post('project_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			//'empId'						 => implode(',',$this->input->post('empId')), // Store employees with comma separate 
			'task_name' 				 => $this->input->post('task_name'),
			'task_desc' 				 => $this->input->post('task_desc'),
			'status'				 	 => $this->input->post('status'),
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	        $this->task_model->update_task($data , $task_Id);
		
		    echo  '<div style="color:#FF0000; font-size:16px; padding:30px;">Task Added Successfully Based On Client with Related Projects. Please Wait it will redirect to task listing page</div>';
	       
		    echo  '<script>setTimeout(function(){window.location.href="'.base_url('task').'"},3000);</script>'; 
			
	    }else{
			
		    $data = array(			
				'status'				 	 => $this->input->post('status'),
			);		
			
			  $this->task_model->update_task($data , $task_Id);
				
			  echo '<div style="color:#FF0000; font-size:16px; padding:30px;">Task Already Added On Partiular Client and Project. Please Wait it will redirect to task listing page</div>';
   	  		  
			  echo '<script>setTimeout(function(){window.location.href="'.base_url('task').'"},3000);</script>'; 
		
		}
		
	
	}

    public function delete(){
  
        $task_Id  = $this->input->post('task_Id');
		   
			if(!empty($task_Id)):
			
				$del = $this->task_model->delete_task($task_Id);
				
			endif;	
  
  }
  
  
  
  #uniqueness of task based on client and projects
    function exists_tasks($str){ #uniqueness of Car Model
	
        $client_Id = $this->input->post('client_Id');
		
		$project_Id = $this->input->post('project_Id');
		
		$task_name = $this->input->post('task_name');
		
		$query = $this->db->get_where('task_details',array('task_name'=>$task_name,'client_Id'=>$client_Id,'project_Id'=>$project_Id));
	
		$countClientProjectTask = $query->num_rows(); 
		
        if ($countClientProjectTask  == 0){
		
            return TRUE;
			
        }else{
		
            $this->form_validation->set_message('exists_tasks', 'Task name already exit. Please try another task name!');
            
			 return FALSE;
        }
    }
	
	
/******************************************************** Task Mapping functionality Purpose of Mapping Project wise taks ************************************************************************************/


	public function taskmaping(){
	
	  
	   		$this->load->view('task/task_mapping');
			
	 
	}


/******************************************************** Task Mapping functionality Purpose of Mapping Project wise taks ********************************************************/
	
	
	
	public function saveTaskMappingData(){  // Function Start
		
	//if ($_POST) {   // POST Statement Start
	
    $data = array();

	
    for ($i = 0; $i < count($this->input->post('task_name')); $i++) {  //For Loop Start
	
	$taskAREQ  = $this->db->select('*')->from('task_details')
									   ->where('client_Id' , $this->input->post('client_Id'))
									   ->where('project_Id' , $this->input->post('project_Id'))
									  // ->where('task_name' , $this->input->post('task_name')[$i])
									    ->where('task_name' , $this->input->post('task_name')) // Removed $i in list--comment in above
									   ->get();
	
	if($taskAREQ->num_rows() == 0 ):  // If Condition Start
        $data[$i] = array(
            'client_Id' => $this->input->post('client_Id'),
            'project_Id' => $this->input->post('project_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
            //'task_name' => $this->input->post('task_name')[$i],
            'task_name' => $this->input->post('task_name'),// Removed $i in list--comment in above
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
            
        );

		echo '<div style="color:#FF0000; font-size:16px; padding:30px;">Task Added Successfully Based On Client with Related Projects. Please Wait it will redirect to task listing page</div>';
		echo '<script>setTimeout(function(){window.location.href="'.base_url('task').'"},3000);</script>'; 
					
    else:	
	   
	  echo '<div style="color:#FF0000; font-size:16px; padding:30px;">Task Already Added On Partiular Client and Project. Please Wait it will redirect to task listing page</div>';
   	  echo '<script>setTimeout(function(){window.location.href="'.base_url('task').'"},3000);</script>'; 
	  // redirect('task');
			
	endif; // If Conditon End
	   
      } //For Loop Start
	  
	  $this->task_model->create_task_mapping($data);
	  
	 
	 //  redirect('task');
	   
     //}  // POST Statement Start
	 
   } // Function END	 
	
	



}
