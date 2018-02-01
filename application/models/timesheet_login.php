<?php
/**
 * eLogivc Admin Panel for Codeigniter 
 * Author: Laxmikanth 
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timesheet_Login extends CI_Model {

    public function __construct() {
	
			parent::__construct();   
	
	}
  

	// Read data using username and password
	public function login($data) {
	
		//$condition = "username =" . "'" . $data['username'] . "' AND " . "password =" . "'" . md5($data['password']). "' AND " . "user_type='admin'";
		
		$condition = "username =" . "'" . $data['username'] . "' AND " . "password =" . "'" . $data['password']. "' AND " . "status ='Active'";
	    
		$query = $this->db->select('*')->from('employee_details')->where($condition)->limit(1)->get();
		
		if ($query->num_rows() == 1) {
		
			return true;
			
		} else {
		
			return false;
			
		}
	}
	
	
  // Read data from database to show data in admin page
	public function user_information($username) {
	
	$condition = "username =" . "'" . $username . "'";
	
	$query =  $this->db->select('*')->from('employee_details')->where($condition)->limit(1)->get();
	
		if ($query->num_rows() == 1) {
		
			return $query->result();
			
		}else{
		
			return false;
			
		}
   }


 /************************************************************* Employee Informatoin store to database base ******************************************************************************************/
 
 
  public function add_employee($data){   // Save User Information here 
   
	  if($data):
	   
	   		 $this->db->insert('employee_details', $data);	
	   
	   endif;
   }

 
  public function getEmployees($updateID = NULL){  // Showing list of employee information and displaying particular employee update information
  
     if(empty($updateID)){
	 
	 		$userQ  = $this->db->select('*')->from('employee_details')->order_by('empId' , 'desc')->get();
	 	}
	
	  else{
	  
	  		$userQ  = $this->db->select('*')->from('employee_details')->where('empId' , $updateID)->get();
	  }
			  
	 return $userQ->result();
  
  }
  
  public function update_employee($data , $empId){
  
  		$this->db->where('empId', $empId);
		
	    $update = $this->db->update('employee_details', $data);
		
		if($update):
			
			  return true; 
			
		endif;
  
  }
  
  /* public function del_employee($empId){
  	
		$this->db->where('empId', $empId)->delete('employee_details');
		  
		$deleteQuery = $this->db->affected_rows();
			
	    echo  $deleteQuery;
  
  } */
  
    public function update_employee_status($empId , $status){
  	
		
		$update = $this->db->set('status',$status)->where('empId',$empId) ->update('employee_details');
		
		$userQ  = $this->db->select('empId,status')->from('employee_details')->where('empId' , $empId)->get()->result();
		
		foreach($userQ as $key => $getStatus ) { 
		
		   if($getStatus->status == 'Active'){
			   
			  $activeClass =  'fa fa-check-circle label label-success';
			   
		   }else{
		   
				$activeClass =  'fa fa-ban label label-danger';
		   
		   }			   
			   
		 	echo  "<a class='".$activeClass."' style=cursor:pointer; onClick=update_emp_status(".$getStatus->empId.",'".$getStatus->status."')> ".$getStatus->status."</a>"; 
		
		}
  }
  
  
  public function recentEmployees(){ // Recent Clients displaying angular js 
	
	     $getREQ  =  $this->db->select('*')->from('employee_details')->order_by('empId' , 'desc')->limit(5)->get();
		
	     return $getREQ->result();
		 
        //echo json_encode($recentQ->result());
	
	
	 }
  
  public function getEmployeeName(){ // Getting List of Users
	
		 $employeeNQ  = $this->db->select('empId,username')->from('employee_details')->order_by('empId' , 'desc')->get();
		 
		 return $employeeNQ->result();
		
	}
  
	
/************************************************************* Employee Informatoin store to database base ******************************************************************************************/


	public function updateChangePassword($password , $empId ){ // Employee can change password
		
		 
		   $update =  $this->db->set('password', md5(strtolower($password)))->where('empId', $empId)->update('employee_details');  //table name
		  
		   if($update):
				
				  return true; 
				
			endif;
			
		   
		}


}

