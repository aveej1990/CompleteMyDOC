<?php
/**
 * eLogivc Admin Panel for Codeigniter 
 * Author: Laxmikanth 
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_Model extends CI_Model {

    public function __construct() {
	
			parent::__construct();   
	
	}
  

	// Read data using username and password
	
	
	public function add_client($data){  // Add Client Model 
	
	  if($data):
	   
	   		 $this->db->insert('client_details', $data);	
	   
	   endif;
	
	
	}
	
	public function getClients($client_Id = NULL){
	
		 
		if(empty($client_Id)):
		
			
			if($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager'){
				
			  $logedInUser = $this->session->userdata['logged_in_timesheet']['empId'];	
			  
			  $clientQ =	 $this->db->select('c.*,e.name,e.empId')->from('client_details as c')
							 ->join(' employee_details as e ', 'e.empId = c.empId', 'left')
							 ->where('c.empId',$logedInUser)
							 ->where('c.status','Active')->order_by('client_Id' , 'desc')->get();
			}else{
				
				$clientQ =	 $this->db->select('c.*,e.name,e.empId')->from('client_details as c')
							 ->join(' employee_details as e ', 'e.empId = c.empId', 'left')
							 ->where('c.status','Active')->order_by('client_Id' , 'desc')->get();
			}			
																
		else:
		
			$clientQ  = $this->db->select('*')->from('client_details')
									->where('client_Id' , $client_Id)->get();
		
		 endif; 
		
		 
		 
		 return $clientQ->result();
	
	}
	
	
	public function update_client($data , $client_Id){ // Clent Update Functionality
  
  		$this->db->where('client_Id', $client_Id);
		
	    $update = $this->db->update('client_details', $data);
		
		if($update):
			
			  return true; 
			
		endif;
  
  }
  
  
   public function del_client($client_Id){
  	
		$this->db->where('client_Id', $client_Id)->delete('client_details');
		  
		$deleteQuery = $this->db->affected_rows();
			
	    echo  $deleteQuery;
  
  }
	
	
	public function getClientName(){ // Get List of Clients
	
		 $clientNQ  = $this->db->select('c.client_Id,c.client_name')->from('client_details as c')->order_by('client_Id' , 'desc')->get();
		 
		 return $clientNQ->result();
		
	}
	
	
	public function recentClients(){ // Recent Clients displaying angular js 
	
	    $recentQ =	 $this->db->select('c.*,e.name,e.empId')->from('client_details as c')
												            ->join('employee_details as e ', 'e.empId = c.empId', 'left')
															->where('c.status','Active')->order_by('client_Id' , 'desc')->limit(5)->get();
																 
	     return $recentQ->result();
		 
        //echo json_encode($recentQ->result());
	
	
	 }
	 
	 
	

}

