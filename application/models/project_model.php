<?php
/**
 * eLogivc Admin Panel for Codeigniter 
 * Author: Laxmikanth 
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project_Model extends CI_Model {

    public function __construct() {
	
			parent::__construct();   
	
	}
  

	// Read data using username and password
	
	
	public function add_project($data){  // Add Client Model 
	
	  if($data):
	   
	   		 $this->db->insert('project_details', $data);	
	   
	   endif;
	
	
	}
	
	public function getProjects($projct_Id = NULL){
	
		 
		if(empty($projct_Id)):
		
			
	    if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'){
			
		$logedInUser = $this->session->userdata['logged_in_timesheet']['empId'];
			
			$projectQ = $this->db->select('p.*,c.client_Id,c.client_name,e.name,e.empId')
						->from('project_details as p')
						->join('client_details as c ', 'p.client_Id = c.client_Id', 'left')
						->join('employee_details as e ', 'p.empId = e.empId', 'left')
						->where('p.empId',$logedInUser)
						->order_by('project_Id' , 'desc')->get();
																
		}else{
			
			$projectQ = $this->db->select('p.*,c.client_Id,c.client_name,e.name,e.empId')
						->from('project_details as p')
						->join('client_details as c ', 'p.client_Id = c.client_Id', 'left')
						->join('employee_details as e ', 'p.empId = e.empId', 'left')
						->order_by('project_Id' , 'desc')->get();
			
		}	
		 
		 
		 else:
		
		         $projectQ  	= $this->db->select('*')->from('project_details')->where('project_Id' , $projct_Id)->get();
		
		 endif; 
		
		 //echo $this->db->last_query();
		 
		 return $projectQ->result();
	
	}
	
	
	public function update_project($data , $projct_Id){ // Clent Update Functionality
  
  		$this->db->where('project_Id', $projct_Id);
		
	    $update = $this->db->update('project_details', $data);
		
		if($update):
			
			  return true; 
			
		endif;
  
  }
  
  
   public function delete_project($project_Id){
    
	 
	 	$this->db->where('project_Id', $project_Id)->delete('project_details');
		  
		$deleteQuery = $this->db->affected_rows();
			
	    echo  $deleteQuery;
  
  }
  
  
  
	public function recentProjects(){ // Recent Clients displaying angular js 
	
	    $projectQ 			=	 $this->db->select('p.*,c.client_Id,c.client_name')->from('project_details as p')
																 ->join('client_details as c ', 'p.client_Id = c.client_Id', 'left')
																  ->order_by('project_Id' , 'desc')->limit(5)->get();
		
		
	     return $projectQ->result();
		 
        //echo json_encode($recentQ->result());
		
		
	
	
	 }
	
	
	
	public function getProjectName($taskClientId){ // Displaying List of projects
	
	    if(empty($taskClientId)) :
			
			$projectNamesQ  	= $this->db->select('project_Id,project_name')->from('project_details')->order_by('project_Id' , 'desc')->get();
			
		else:
			
			 $projectNamesQ  	= $this->db->select('project_Id,project_name')->from('project_details')->where('client_Id',$taskClientId)->order_by('project_Id' , 'desc')->get();
			
		endif;  	
			
			  return $projectNamesQ->result();
			
			
		
	}
	

}

