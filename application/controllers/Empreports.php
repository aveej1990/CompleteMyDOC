<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empreports extends CI_Controller {

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
		
		$this->load->model('client_model');
		
		$this->load->model('project_model');
		
		$this->load->model('task_model');
		
		$this->load->model('emptimelog_model');
		
		$this->load->helper('text');
		
		
		if(empty($this->session->userdata['logged_in_timesheet'])){

			redirect('home/login');
		}
		
	}
	
	public function index(){

		$userType = $this->session->userdata['logged_in_timesheet']['user_type'];

		$data['getRecords'] = $this->emptimelog_model->getRecords($userType);

		$this->load->view('employee_reports/employee_timelog' , $data);

			//$this->load->view('employee_reports/add_employee_timelog');


	}
	
	public function add($emp_record_id = NULL){

		if(empty($emp_record_id)) : 
			
			$this->load->view('employee_reports/add_employee_timelog');

		else:
			
			$data['updateEmpRecord'] = $this->emptimelog_model->getUpdateEmpRecords($emp_record_id);

		    	//$this->load->view('employee/add_employees' , $data);

			$this->load->view('employee_reports/add_employee_timelog' , $data);

		endif;	   


	}
	
	
	public function add_emp_records(){

		if(!empty($this->session->userdata['logged_in_timesheet']['empId'])) :

			$data = array(
				'empId' 					 => $this->session->userdata['logged_in_timesheet']['empId'],
				'client_Id' 				 => $this->input->post('client_Id'),
				'project_Id' 				 => $this->input->post('project_Id'),
			'task_Id' 			 		 => implode(',',$this->input->post('task_Id')), // Store task with comma separate
			'team_member_type'			 => $this->input->post('team_member_type'),
			'emp_time_hours'			 => $this->input->post('emp_time_hours'),
			'comments' 			 		 => $this->input->post('comments'),
			'emp_report_dates' 			 => $this->input->post('emp_report_dates'),
			'created_at'    	 		 => date('Y-m-d H:i:s'),
			'updated_at' 				 => date('Y-m-d H:i:s')
		);


			//Get Project Manager email id based on Project.
			
			$getStoredDetails = $this->emptimelog_model->addEmpRecords($data);
			//redirect('empreports');
			//if(!empty($getStoredDetails)){ 
			
			$getListOfProjects   	= $this->emptimelog_model->getAddedReportTaskNames($getStoredDetails[0]->task_Id); // List of tasks
			
			//echo '<pre>'; print_r($getListOfProjects); exit;
			
			$to_email = /*$getStoredDetails[0]->email. ', '. */'vemulurisuresh90@gmail.com'; // mail IDs
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; //use double quotes
            $this->email->initialize($config);                        
	        //send mail 
            $this->email->from('suresh@gdresearchcenter.com', 'eLogic Timesheet');
            $this->email->to($to_email);
            $this->email->subject('Task Report' , 'eLogic Timesheet');
            $body = '<!doctype html><html><head><meta charset="utf-8"><title>eLogic Tech</title></head><body style="width: 95%; margin: 0 auto; background: #f1f1f1; border:1px solid #888; padding: 0 1% 2% 1% "><div align="left" style="margin: 3% auto 2% 6%;"> <img src="http://www.elogictechsolutions.com/assets/images/logo.png" style="width: 180px;"> </div><div style="background: #004b88; padding: 2%; border-radius: 15px; margin-top: 3%;"> <section style="background: #004b88; border-radius: 6px; padding-top: 2%; font-size: 17px;"> <div style=" color: #fff; margin:2% auto 0px auto; padding-left: 6%;">Dear '.ucwords($getStoredDetails[0]->name).', </div> <div align="left" style=" margin: 1% auto; padding-left: 6%; line-height: 24px; color: #fff;"> <p>I have entered the timesheet for '.date('d-F-Y',strtotime($getStoredDetails[0]->emp_report_dates)).'. </p> </div> <div align="left" style=" margin: 1% auto; padding-left: 6%; line-height: 24px; color: #fff;"> <table> <tbody> <tr> <td width="20%"> Client Name : </td> <td> '.$getStoredDetails[0]->client_name.' </td> </tr> <tr> <td> Project Name : </td> <td> '.$getStoredDetails[0]->project_name.' </td> </tr> <tr> <td> Description : </td> <td> '.$getListOfProjects.' </td> </tr><tr> <td> Status  : </td> <td> '.$getStoredDetails[0]->status.' </td> </tr><tr> <td> Date : </td> <td> '.date('d-F-Y',strtotime($getStoredDetails[0]->emp_report_dates)).' </td> </tr> <tr> <td> Hours : </td> <td> '.$getStoredDetails[0]->emp_time_hours.' hrs </td> </tr> <tr> <td align="top" style="position: relative; top: 0px;"> Comments : </td> <td> '.$getStoredDetails[0]->comments.'. </td> </tr> </tbody> </table> </div> <div align="left" style=" margin: 3% auto 0 6%; line-height: 24px; color: #fff; ">Thanks & Regards, <br> <div style="color: #fff; padding-bottom: 4%;">'.ucwords($this->session->userdata['logged_in_timesheet']['name']).' </div> </div> </section></div></body></html>';

            $this->email->message($body);
            
            $this->email->send();

            redirect('empreports');

			//}else{

				//$this->emptimelog_model->addEmpRecords($data);

				//redirect('empreports');
			//}

			//Get Project Manager email id based on Project.



        endif;


    }


    public function update_emp_records() {


    	if(!empty($this->session->userdata['logged_in_timesheet']['empId'])) :

    		$emp_record_id = $this->input->post('emp_record_id');


    		$data = array(
    			'client_Id' 				 => $this->input->post('client_Id'),
    			'project_Id' 				 => $this->input->post('project_Id'),
			'task_Id' 			 		 => implode(',',$this->input->post('task_Id')), // Store task with comma separate
			'team_member_type'			 => $this->input->post('team_member_type'),
			'emp_time_hours'			 => $this->input->post('emp_time_hours'),
			'comments' 			 		 => $this->input->post('comments'),
			'emp_report_dates' 			 => $this->input->post('emp_report_dates'),
			'updated_at' 				 => date('Y-m-d H:i:s')
		);

    		$this->emptimelog_model->updateEmpRecords($data , $emp_record_id);

    		redirect('empreports');

    	endif;


    }


	public function delete(){  // Delete employee single record into databasse

		$emp_record_id  = $this->input->post('emp_record_id');

		if(!empty($emp_record_id)):
			
			$del = $this->emptimelog_model->deleteEmpRecord($emp_record_id);

		endif;	
	}
	
	
	public function getListOfProjectsWithClient(){  // Getting Client wise projects

		$client_Id  = $this->input->post('client_Id'); 

		if(!empty($client_Id)) :

			$getProjects = $this->emptimelog_model->getListOfProjectsWithClient($client_Id);

		endif; 

	 }  // Getting Client wise projects END
	 
	 
	 public function getClientProjects(){  // Getting Client wise projects

	 	$client_Id  = $this->input->post('client_Id'); 

	 	if(!empty($client_Id)) :

	 		$getProjects = $this->emptimelog_model->getClientWiseProjects($client_Id);

	 	endif; 

	 }  // Getting Client wise projects END
	 

	public function getProjectsTask(){ // Getting Project wise task

		$project_Id  = $this->input->post('project_Id'); 

		if(!empty($project_Id)) :

			$getTask = $this->emptimelog_model->getProjectWiseTask($project_Id);

		endif; 

	} // Getting Project wise task END
	
	
	
	public function searchReportLog(){  // Search Employee Lime Log

		$empid= $this->input->post('client_Id');

		if(!empty($empid)) :

			$params = array(
				'client_Id' => $this->input->post('client_Id'),
				'project_Id' => $this->input->post('project_Id'),
				'form_date' => $this->input->post('form_date'),
				'to_date' => $this->input->post('to_date'),            
			);

			$data['resultTimeLog'] = $this->emptimelog_model->getSearchEmpTimeLog($params);     

			$this->load->view('employee_reports/employee_timelog_search' , $data);

		else : 

			$this->load->view('employee_reports/employee_timelog_search');

		endif; 	 


		
	}
	

   public function cPass(){  //Change Password

   	$pass=$this->input->post('password');

   	if(!empty($pass)):

   		$password		 = 	$this->input->post('password');

   		$employeeName    =  $this->session->userdata['logged_in_timesheet']['username'];

   		$this->emptimelog_model->updateChangePassword( $password , $employeeName );

   		$this->session->set_flashdata('msg', 'Your Password Successfully Changed!</span>');

   		redirect(base_url().'empreports/cPass');


   	else:

   		$this->load->view('employee_reports/changepassword');

   	endif;	


   }	


   public function searchProjectsTask(){ // Getting Project wise task

   	$project_Id  = $this->input->post('project_Id'); 

   	if(!empty($project_Id)) :

   		$getTask = $this->emptimelog_model->searchProjectWiseTask($project_Id);

   	endif; 

	} // Getting Project wise task END	
	
	public function update_emp_report_status(){

		$emp_record_id 	 	 = $this->input->post('emp_record_id');
		$status 			 = $this->input->post('status');

		if(!empty($emp_record_id)):
			
			$updateStatus = $this->emptimelog_model->update_emp_report_status($emp_record_id , $status);

		endif;	

	}
	
}

