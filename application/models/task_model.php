<?php
/**
 * eLogivc Admin Panel for Codeigniter 
 * Author: Laxmikanth 
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Task_Model extends CI_Model {

    public function __construct() {
	
			parent::__construct();   
	
	}
  

	// Read data using username and password
	
	
	public function add_task($data){  // Add Task  
	
	  
	  if($data):
	   
	   		 $this->db->insert('task_details', $data);	
	   
	   endif;
	
	
	}
	
	public function getTaskList($task_Id = NULL){
	
		 
		if(empty($task_Id)):
		
			if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'){			
				$logedInUser = $this->session->userdata['logged_in_timesheet']['empId'];
		
					$taskQ = $this->db->select('t.*,c.client_Id,c.client_name,p.project_Id,p.project_name,e.name,e.empId')
								->from('task_details as t')
								->join('client_details as c ', 't.client_Id = c.client_Id', 'left')
								->join('project_details as p ', 't.project_Id = p.project_Id', 'left')
								->join('employee_details as e ', 't.empId = e.empId', 'left')
								->where('t.empId',$logedInUser)
								->order_by('task_Id' , 'desc')->get();
																
			
			}else{
				
					$taskQ = $this->db->select('t.*,c.client_Id,c.client_name,p.project_Id,p.project_name,e.name,e.empId')
								->from('task_details as t')
								->join('client_details as c ', 't.client_Id = c.client_Id', 'left')
								->join('project_details as p ', 't.project_Id = p.project_Id', 'left')
								->join('employee_details as e ', 't.empId = e.empId', 'left')
								->order_by('task_Id' , 'desc')->get();
					
			}	
		
		else:
		
		    $taskQ  = $this->db->select('*')->from('task_details')->where('task_Id' , $task_Id)->get();
		
		endif; 
		
		 // echo $this->db->last_query();
		 
		 return $taskQ->result();
	
	}
	
	
	public function update_task($data , $task_Id){ // Clent Update Functionality
  
  		$this->db->where('task_Id', $task_Id);
		
	    $update = $this->db->update('task_details', $data);
		
		if($update):
			
			  return true; 
			
		endif;
  
  }
  
  
   public function delete_task($task_Id){
    
	 
	 	$this->db->where('task_Id', $task_Id)->delete('task_details');
		  
		$deleteQuery = $this->db->affected_rows();
			
	    echo  $deleteQuery;
  
  }
  
  
  
  public function getTaskName($taskProjectId){ // Displaying List of projects
  
         // echo '$getUpdateId-------'.$getUpdateId; exit;
	
			if(empty($taskProjectId)) :
			
				$taskNamesQ  	= $this->db->select('task_Id,task_name')->from('task_details')->group_by('task_name')->order_by('task_Id' , 'desc')->get();
			
			else:
			
				$taskNamesQ  	= $this->db->select('task_Id,task_name')->from('task_details')->where('project_Id',$taskProjectId)->order_by('task_Id' , 'desc')->get();
			
			endif;  	
			
			return $taskNamesQ->result();
		
	}
	
	
	
	public function create_task_mapping($data) {
	
  
       //$this->db->insert_batch('task_details', $data);
	   
	   if($data){
		
			$this->db->insert_batch('task_details', $data);
	
	   }
	
	
     }
	
	

}

