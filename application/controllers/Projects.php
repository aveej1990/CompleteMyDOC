<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

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
		
		if(empty($this->session->userdata['logged_in_timesheet'])){
		
			redirect('home/login');
		}
		
    }
	
	public function index(){
		
			$data['getProjects'] = $this->project_model->getProjects();
			
			$this->load->view('projects/projects' , $data);
			
	}
	
	public function add($projct_Id = NULL){
	
	   if(empty($projct_Id)) : 
			
			 $this->load->view('projects/add_project');
			 
		else:
			
			 $data['updateProject'] = $this->project_model->getProjects($projct_Id);
	
		     $this->load->view('projects/add_project' , $data);
					
		endif;	   
			
	 
	}
	
	public function addproject(){ // Adding new Client function. 	
			
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('project_name', 'Project name already exit. Please try another project', 'required|trim|callback_exists_projects');
		
		  //$this->form_validation->set_rules('task_name', 'Task name already exit. Please try another task name', 'required|trim|callback_exists_tasks');
		
		if ($this->form_validation->run() == FALSE) {
	
			$this->load->view('projects/add_project');
			
	    }else{
						
			$data = array(
			'client_Id' 				 => $this->input->post('client_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'project_name' 				 => $this->input->post('project_name'),
			'project_desc' 				 => $this->input->post('project_desc'),
			'project_cost' 				 => $this->input->post('project_cost'),
			'project_start_date' 		 => $this->input->post('project_start_date'),
			'project_end_date'			 => $this->input->post('project_end_date'),
			'status'				 	 => $this->input->post('status'),
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->project_model->add_project($data);
		
		 redirect('projects');
		
	   }
	
	}
	
	
	public function updateproject(){ // Adding new Client function. 	
			
		
		$projct_Id = $this->input->post('project_id');
		
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('project_name', 'Project name already exit. Please try another project', 'required|trim|callback_exists_projects');
		
		if ($this->form_validation->run() == FALSE) {
	
			$this->load->view('projects/add_project');
			
			$data = array(
			'client_Id' 				 => $this->input->post('client_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'project_desc' 				 => $this->input->post('project_desc'),
			'project_cost' 				 => $this->input->post('project_cost'),
			'project_start_date' 		 => $this->input->post('project_start_date'),
			'project_end_date'			 => $this->input->post('project_end_date'),
			'status'				 	 => $this->input->post('status'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->project_model->update_project($data , $projct_Id);
		
		 redirect('projects');
			
	    }else{
						
			$data = array(
			'client_Id' 				 => $this->input->post('client_Id'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'project_name' 				 => $this->input->post('project_name'),
			'project_desc' 				 => $this->input->post('project_desc'),
			'project_cost' 				 => $this->input->post('project_cost'),
			'project_start_date' 		 => $this->input->post('project_start_date'),
			'project_end_date'			 => $this->input->post('project_end_date'),
			'status'				 	 => $this->input->post('status'),
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->project_model->update_project($data , $projct_Id);
		
		 redirect('projects');
		
	   }
		
	
	}

  public function delete(){
  
        $project_Id  = $this->input->post('project_Id');
		   
			if(!empty($project_Id)):
			
				$del = $this->project_model->delete_project($project_Id);
				
			endif;	
  
  }
  
  
  public function getRecentProjects(){  //Get Recent Clients Angular js funciton  
  
    	$recentProjectInfo = $this->project_model->recentProjects();
		
		echo json_encode($recentProjectInfo);
  
  }
	
	
	
  #uniqueness of task based on client and projects
    function exists_projects($str){ #uniqueness of Car Model
	
        $client_Id = $this->input->post('client_Id');
		
		$project_name = $this->input->post('project_name');
		
		$query = $this->db->get_where('project_details',array('project_name'=>$project_name,'client_Id'=>$client_Id));
	
		$countClientProject = $query->num_rows(); 
		
        if ($countClientProject  == 0){
		
            return TRUE;
			
        }else{
		
            $this->form_validation->set_message('exists_projects', 'Project name already exit particular client. Please try another project!');
            
			 return FALSE;
        }
    }	
	



}
