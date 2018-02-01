<?php
/**
 * eLogivc Admin Panel for Codeigniter 
 * Author: Laxmikanth 
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emptimelog_Model extends CI_Model {

    public function __construct() {
	
			parent::__construct();   
	
	}
  

	
	 public function getClientWiseProjects($client_Id){ // Get Dropdown client wise projects 
	
		  
	  $getProjects  = $this->db->select('*')->from('project_details')->where('client_Id', $client_Id)->get()->result();
	  
	   echo '<option value="">Please Select Project</option> <option value="all">All</option>';
	  
	   foreach($getProjects as $key => $getResult):
	   
	    echo '<option value='.$getResult->project_Id.'>'.$getResult->project_name.'</option>';
	   
	   endforeach;
	 
	  //return $getStates;
		 
	}  // Get Dropdown client wise projects 
	
	
	public function getListOfProjectsWithClient($client_Id){ // Get Dropdown client wise projects  with out all feature
	
		  
	  $getProjects  = $this->db->select('*')->from('project_details')->where('client_Id', $client_Id)->get()->result();
	  
	   echo '<option value="">Please Select Project</option>';
	  
	   foreach($getProjects as $key => $getResult):
	   
	    echo '<option value='.$getResult->project_Id.'>'.$getResult->project_name.'</option>';
	   
	   endforeach;
	 
	  //return $getStates;
		 
	}  // Get Dropdown client wise projects 
	
	
	public function getProjectWiseTask($project_Id){ // Get Dropdown Project wise clients
	
			
	 $getTask  = $this->db->select('*')->from('task_details')->where('project_Id', $project_Id)->get()->result();
	  
	   foreach($getTask as $key => $getResult):
	   
	    echo '<option value='.$getResult->task_Id.'>'.$getResult->task_name.'</option>';
	   
	   endforeach;
	
	}
	 
	 
	 
  public function addEmpRecords($data) { // Store Emploee Records into database
  
   if($data):
	   
	   	$this->db->insert('emp_record_details', $data);

		$emp_reportlog_id = $this->db->insert_id();
		
					 
			if(!empty($emp_reportlog_id)){
			
				$this->db->select('er.task_Id,er.emp_time_hours,er.comments,er.emp_report_dates,er.status,emp.name,emp.email,c.client_name,p.project_name');
				$this->db->from('emp_record_details er'); 
				$this->db->from('project_details p');
				$this->db->join('employee_details as emp', 'emp.empId=p.empId', 'left');
				$this->db->join('client_details as c', 'c.client_Id=p.client_Id', 'left');
				$this->db->where('p.project_Id  = ',$data['project_Id']);
				$this->db->where('er.emp_record_id',$emp_reportlog_id);
				$this->db->order_by('er.emp_record_id','desc');         
				$recordsQ = $this->db->get(); 
			
			    return $recordsQ->result();
			
			}
			 
        			 
	   
	   endif;
  
  }	 
  
  
  public function getRecords($userType){ // List of employee enter the records based on users
  
        if($userType == 'developer'):
		    
			$empId =  $this->session->userdata['logged_in_timesheet']['empId'];
			
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name');
            $this->db->from('emp_record_details er'); 
            $this->db->join('employee_details as emp', 'emp.empId=er.empId', 'left');
			$this->db->join('client_details as c', 'c.client_Id=er.client_Id', 'left');
            $this->db->join('project_details as p', 'p.project_Id=er.project_Id', 'left');
			$this->db->join('task_details as t', 't.task_Id=er.task_Id', 'left');
            $this->db->where('er.empId',$empId);
            $this->db->order_by('er.emp_record_id','desc');   
			
		elseif($userType == 'manager'):
		
			$empId =  $this->session->userdata['logged_in_timesheet']['empId'];
			
			$getProjectId = $this->getProjects($empId);			
			if(!empty($getProjectId)){
				$eProjectIds  = $getProjectId;
			}else{
				$eProjectIds  = '';
			}
			
			//$exp_projectIds = implode(',' ,$getProjectId);
			
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name');
            $this->db->from('emp_record_details er'); 
            $this->db->join('employee_details as emp', 'emp.empId=er.empId', 'left');
			$this->db->join('client_details as c', 'c.client_Id=er.client_Id', 'left');
            $this->db->join('project_details as p', 'p.project_Id=er.project_Id', 'left');
			$this->db->join('task_details as t', 't.task_Id=er.task_Id', 'left');
			$this->db->where_in('er.project_Id',$eProjectIds);
			$this->db->or_where('er.empId',$empId);
            $this->db->order_by('er.emp_record_id','desc'); 
		   
		else: 
		    $this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name');
            $this->db->from('emp_record_details er'); 
            $this->db->join('employee_details as emp', 'emp.empId=er.empId', 'left');
			$this->db->join('client_details as c', 'c.client_Id=er.client_Id', 'left');
            $this->db->join('project_details as p', 'p.project_Id=er.project_Id', 'left');
			$this->db->join('task_details as t', 't.task_Id=er.task_Id', 'left');
           // $this->db->where('er.empId',$empId);
            $this->db->order_by('er.emp_record_id','desc');         
          endif;	
		  $recordsQ = $this->db->get(); 
		 //echo 'Project Manager----------------------'.$this->db->last_query();			
		 return $recordsQ->result();
  }
  
  
   public function getUpdateEmpRecords($emp_record_id){ //Get UPdate Records on Particular Users only
   
       $empURQ  	= $this->db->select('*')->from('emp_record_details')->where('emp_record_id' , $emp_record_id)->get();
	  
	   return $empURQ->result();
   
   
   }
   
   public function updateEmpRecords($data , $emp_record_id){ // Update Employee records    
   
   $this->db->where('emp_record_id', $emp_record_id);
		
	    $update = $this->db->update('emp_record_details', $data);
		
		if($update):
			
			  return true; 
			
		endif;
   
   }
   
   public function deleteEmpRecord($emp_record_id){ // Delete Employee Records
   
   
   	$this->db->where('emp_record_id', $emp_record_id)->delete('emp_record_details');
		  
		$deleteQuery = $this->db->affected_rows();
			
	    echo  $deleteQuery;
   
   
   }
	
	
	public function getSearchEmpTimeLog($params){  //
	
		$client_Id 		 = 	 $params['client_Id'];
        $project_Id      =	 $params['project_Id'];
        $form_date		 =	 $params['form_date'];
        $to_date		 = 	 $params['to_date'];
		
		$empId =  $this->session->userdata['logged_in_timesheet']['empId']; // Loged in users		
		
		if($this->session->userdata['logged_in_timesheet']['user_type'] == 'developer') { // Only Search on usertype developer only
		
		if($client_Id == 'all' && $project_Id == 'all') :   // Checking all records based on from and to dates only.
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->where('er.empId',$empId)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'All---------'.$this->db->last_query();
			
			return $recordsQ->result();
			
			//echo $this->db->last_query();        
			
		elseif($project_Id == 'all'):
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)		
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->where('er.empId',$empId)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			//echo '--project--'.$this->db->last_query(); 
			return $recordsQ->result();
			
			//echo $this->db->last_query();   	
			 
        
		else:
		    
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)			
			->where('er.project_Id = ',$project_Id)
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->where('er.empId',$empId)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'Particula'.$this->db->last_query();  
			
			return $recordsQ->result();
		
		endif;
		
		
	  }
	  
	  elseif($this->session->userdata['logged_in_timesheet']['user_type'] == 'manager') { // Only Search on usertype manager only it's get it's particular project details only....
	  
		$empId =  $this->session->userdata['logged_in_timesheet']['empId'];
			
			$getProjectId = $this->getProjects($empId);			
			if(!empty($getProjectId)){
				$eProjectIds  = $getProjectId;
			}else{
				$eProjectIds  = '';
		}
		
		if($client_Id == 'all' && $project_Id == 'all') :   // Checking all records based on from and to dates only.
		 $this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
			->where_in('er.project_Id',$eProjectIds)
			->or_where('er.empId',$empId)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'All---------'.$this->db->last_query();
			
			return $recordsQ->result();
			
			//echo $this->db->last_query();        
			
		elseif($project_Id == 'all'):
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)		
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->where_in('er.project_Id',$eProjectIds)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			//echo '--project--'.$this->db->last_query(); 
			return $recordsQ->result();
			
			//echo $this->db->last_query();   	
			 
        
		else:
		    
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)			
			->where('er.project_Id = ',$project_Id)
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->where_in('er.project_Id',$eProjectIds)
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'Particula'.$this->db->last_query();  
			
			return $recordsQ->result();
		
		endif;
	  
	  
	  
	  }else{ // Search for Manager and Admin 
	  
	  
	 		if($client_Id == 'all' && $project_Id == 'all') :   // Checking all records based on from and to dates only.
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'All---------'.$this->db->last_query();
			
			return $recordsQ->result();
			
			//echo $this->db->last_query();        
			
		elseif($project_Id == 'all'):
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)		
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			//echo '--project--'.$this->db->last_query(); 
			return $recordsQ->result();
			
			//echo $this->db->last_query();   	
			 
        
		else:
		    
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)			
			->where('er.project_Id = ',$project_Id)
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			//echo 'Particula'.$this->db->last_query();  
			
			return $recordsQ->result();
			
	    endif;
	  
	  }	
		
	
	} // Employee and Admin Search Report Log END
	
	
	public function updateChangePassword($password , $employeeName ){ // Employee can change password
	
	 
	   $update =  $this->db->set('password', md5(strtolower($password)))->where('username', $employeeName)->update('employee_details');  //table name
	  
	   if($update):
			
			  return true; 
			
		endif;
		
	   
	}
	
	
	public function searchProjectWiseTask($project_Id){ // Get Dropdown Project wise clients
	
			
	 $getTask  = $this->db->select('*')->from('task_details')->where('project_Id', $project_Id)->get()->result();
	  
	    echo '<option value="">Please Select Task</option> <option value="all">All</option>';
	   
	   foreach($getTask as $key => $getResult):
	   
	    echo '<option value='.$getResult->task_Id.'>'.$getResult->task_name.'</option>';
	   
	   endforeach;
	
	}



/********************************************************  Manage Timesheet Report Log in Ussertype Admin Or Manager Added On 04-07-2017 ******************************************************************/
	
	
	
	
	public function getUserTypeAdminReportLog($params){  //
	
	  	$client_Id 		 = 	 $params['client_Id'];
        $project_Id      =	 $params['project_Id'];
		$task_Id     	 =	 $params['task_Id'];
		$empId      	 =	 implode(' ,' ,$params['empId']);
        $form_date		 =	 $params['form_date'];
        $to_date		 = 	 $params['to_date'];
		
		
		
		if($client_Id == 'all' && $project_Id == 'all' && $empId == 'all' && $task_Id == 'all') :   // Checking all records based on from and to dates only.
		
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		    //echo 'List Of All----------'.$this->db->last_query(); 
			 
			return $recordsQ->result();
			
		
		elseif($project_Id == 'all' && $empId == 'all' && $task_Id == 'all'):
		
		$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		   // echo 'List Of All Project----------'. $this->db->last_query(); 
			 
			return $recordsQ->result();
			
			
		elseif($client_Id == 'all' && $project_Id == 'all' && $task_Id == 'all'):
		 $exp_empIds = explode(',' ,$empId);
		$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where_in('er.empId ',$exp_empIds)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		   // echo 'List Of All Project----------'. $this->db->last_query(); 
			 
			return $recordsQ->result();
			
			
		elseif($empId == 'all' && $task_Id == 'all'):
		
		$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)
			->where('er.project_Id  = ',$project_Id)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		    //echo 'List Of All Tasks----------'. $this->db->last_query(); 
			 
			return $recordsQ->result();	
			
			
		elseif($task_Id == 'all'):
		
		   if($project_Id == 'all'){
		   
		    $exp_empIds = explode(',' ,$empId);
		   
		   	$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)
			->where_in('er.empId ',$exp_empIds)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		  // echo 'List Of All Employees----------IF--'. $this->db->last_query(); 
			 
			return $recordsQ->result();	
		   
		   }else{
		   
		   	$exp_empIds = explode(',' ,$empId);
			
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)
			->where('er.project_Id  = ',$project_Id)
			->where_in('er.empId ',$exp_empIds)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		    //echo 'List Of All Employees----------ELSE--'. $this->db->last_query(); 
			 
			return $recordsQ->result();	
		   
		   }
		
		
		
		else:	      
			
			$exp_empIds = explode(',' ,$empId);
			
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
			->from('emp_record_details er')
			->where('er.client_Id  = ',$client_Id)
			->where('er.project_Id  = ',$project_Id)
			->where_in('er.empId ',$exp_empIds)	
			->where('er.emp_report_dates >= ',$form_date)			
			->where('er.emp_report_dates <= ',$to_date)
			->where('er.status','Approved')
            ->join('employee_details as emp', 'emp.empId=er.empId', 'left')
			->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
            ->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
			->join('task_details as t', 't.task_Id=er.task_Id', 'left')
            ->order_by('er.empId','desc');
			
			$recordsQ = $this->db->get(); 
		
		    //echo 'Select wise----------'. $this->db->last_query(); 
			 
			return $recordsQ->result();	
		
		endif;
	 
	} 
	
	public function getAddedReportTaskNames($getTaskIds){ // Multiple task names dispalying here 
		
		//echo $getTaskIds.'<br>';
		$exp_taskids = explode(',' ,$getTaskIds);
		
		$taskQ  = $this->db->select('task_name')->from('task_details')->where_in('task_Id' , $exp_taskids)->get();
		
		$reporttaskName = array();
		foreach($taskQ->result() as $showTaskNames){
			
				$reporttaskName22 = $showTaskNames->task_name;
				
				$reporttaskName[] = $reporttaskName22;				
		}
		
		$tasknames=implode(' , ',$reporttaskName); 
          
		return $tasknames ;
		
	}
	
/********************************************************  Manage Timesheet Report Log in Ussertype Admin Or Manager Added On 04-07-2017 ******************************************************************/

 /******************** Project Manager get it's created project list *********************************/
  
  public function getProjects($empId){
	  
	  //echo 'testing---------------'.$empId;
	  
	  $this->db->select('p.project_Id')->from('project_details p')
									   ->where('p.empId  = ',$empId)
									   ->order_by('p.project_Id','desc');
	  $recordsQ = $this->db->get()->result(); 
	  
	  $getProjectIds = array();
	  
	  foreach($recordsQ as $getProjectId){
		  
		  $getProjectIds[] = $getProjectId->project_Id;
		  
	  } 
	  
	  return $getProjectIds;
	 
  } 
 
  public function update_emp_report_status($emp_record_id , $status){
	  
		$update = $this->db->set('status',$status)->where('emp_record_id',$emp_record_id) ->update('emp_record_details');
		
		$userQ  = $this->db->select('emp_record_id,status')->from('emp_record_details')->where('emp_record_id' , $emp_record_id)->get()->result();
		
		foreach($userQ as $key => $getStatus ) { 
		
		   if($getStatus->status == 'Approved'){
			   
			  $activeClass =  'fa fa-check-circle label label-success';
			   
		   }else{
		   
				$activeClass =  'fa fa-ban label label-danger';
		   
		   }			   
			   
		 	echo  "<a class='".$activeClass."' style=cursor:pointer; onClick=update_emp_report_status(".$getStatus->emp_record_id.",'".$getStatus->status."')> ".$getStatus->status."</a>"; 
		
		}
	  
  }
 
 /******************** Project Manager get it's created project list *********************************/

	public function getRecentApprovedReportLog($userType){
		
		if($userType == 'developer'):
		    
			$empId =  $this->session->userdata['logged_in_timesheet']['empId'];
			
			$this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name');
            $this->db->from('emp_record_details er'); 
            $this->db->join('employee_details as emp', 'emp.empId=er.empId', 'left');
			$this->db->join('client_details as c', 'c.client_Id=er.client_Id', 'left');
            $this->db->join('project_details as p', 'p.project_Id=er.project_Id', 'left');
			$this->db->join('task_details as t', 't.task_Id=er.task_Id', 'left');
            $this->db->where('er.empId',$empId);
			$this->db->where('er.status','Approved');
			$this->db->limit('10');
            $this->db->order_by('er.emp_record_id','desc');   
		
		    $recordsQ = $this->db->get();
		 
		 return $recordsQ->result();
		 
		 endif;
	}
 
  
 
 
 }

