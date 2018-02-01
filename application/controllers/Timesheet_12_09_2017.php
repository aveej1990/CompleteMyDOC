<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheet extends CI_Controller {

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
		$this->load->library('excel'); // load excel library
		$this->load->helper('text');
		
		// Load database		
		$this->load->model('timesheet_login');
		
		$this->load->model('client_model');
		
		$this->load->model('project_model');
		
		$this->load->model('task_model');
		
		$this->load->model('emptimelog_model');
		
				
		if(empty($this->session->userdata['logged_in_timesheet'])){
		
			redirect('home/login');
		}
		
    }
	
	/*public function index(){
	
	    	$this->load->view('timesheet/timesheet');
	
	}
	*/
	
	
	public function index(){  // Search Employee Lime Log
	
	    if(!empty($this->input->post('client_Id'))) :
		
		 $params = array(
            'client_Id' 		=> $this->input->post('client_Id'),
            'project_Id' 		=> $this->input->post('project_Id'),
			'task_Id'			=> $this->input->post('task_Id'),
			'empId' 		  	=> $this->input->post('empId'),
            'form_date'			=> $this->input->post('form_date'),
            'to_date'			=> $this->input->post('to_date'),            
            );
			
		//echo '<pre>'; print_r($params);	 exit;
		
           $data['getManageReportLog'] = $this->emptimelog_model->getUserTypeAdminReportLog($params);     
        
		 	$this->load->view('timesheet/timesheet' , $data);
		 
	   else : 
	      
		    $data['getManageReportLog'] = '';
		    
	      	$this->load->view('timesheet/timesheet',$data);
	   
	   endif; 	 
			
			
		
	}


 
	
	
	
	public function excel()
    {
               
        $client_Id 		 = 	 $_REQUEST['client_Id'];
         $project_Id     =	 $_REQUEST['project_Id'];
		 $task_Id     	 =	 $_REQUEST['task_Id'];
		 $empId      	 =	 $_REQUEST['empId'];
         $form_date		 =	 $_REQUEST['form_date'];
         $to_date		 = 	 $_REQUEST['to_date'];
		 
		 
  

			   $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Time Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', 'Time Report Excel Sheet');
			   $this->excel->getActiveSheet()->setCellValue('A2', 'Sno');
			   $this->excel->getActiveSheet()->setCellValue('B2', 'Employee Name');
                $this->excel->getActiveSheet()->setCellValue('C2', 'Client Name');
                $this->excel->getActiveSheet()->setCellValue('D2', 'Project Name');
				$this->excel->getActiveSheet()->setCellValue('E2', 'Task Name');
				$this->excel->getActiveSheet()->setCellValue('F2', 'Task Hours');
				$this->excel->getActiveSheet()->setCellValue('G2', 'Added Date');
				$this->excel->getActiveSheet()->setCellValue('H2', 'Comments');
                //merge cell A1 until F1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                //set aligment to center for that merged cell (A1 to H1)
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16)->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('E2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('G2')->getFont()->setSize(14)->setBold(true);
				$this->excel->getActiveSheet()->getStyle('H2')->getFont()->setSize(14)->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#4286f4');
       for($col = ord('A'); $col <= ord('H'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                 //change the font size
				 
				$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
				  
                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                 
                if(chr($col) == 'E'){ 
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				}else if(chr($col) == 'H'){ 
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				}else{
					$this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}	
        }
                //retrive contries table data
				
	/* if($client_Id == 'all' && $project_Id == 'all' && $empId == 'all' && $task_Id == 'all') :   // Checking all records based on from and to dates only.
	
	   $recordsQ = $this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
					->from('emp_record_details er')
					->where('er.emp_report_dates >= ',$form_date)			
					->where('er.emp_report_dates <= ',$to_date)
					->join('employee_details as emp', 'emp.empId=er.empId', 'left')
					->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
					->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
					->join('task_details as t', 't.task_Id=er.task_Id', 'left')
					->order_by('er.emp_record_id','desc');  
            $recordsQ = $this->db->get(); 
  
   
   endif; */
   
   /********************** Search From Data And Export Execl Data *******************************************************/
   
   if($client_Id == 'all' && $project_Id == 'all' && $empId == 'all' && $task_Id == 'all') :   // Checking all records based on from and to dates only.
		
			$recordsQ = $this->db->select('er.* ,emp.empId,emp.name,c.client_Id,c.client_name,p.project_Id,p.project_name,t.task_name')
					->from('emp_record_details er')
					->where('er.emp_report_dates >= ',$form_date)			
					->where('er.emp_report_dates <= ',$to_date)
					->where('er.status','Approved')
					->join('employee_details as emp', 'emp.empId=er.empId', 'left')
					->join('client_details as c', 'c.client_Id=er.client_Id', 'left')
					->join('project_details as p', 'p.project_Id=er.project_Id', 'left')
					->join('task_details as t', 't.task_Id=er.task_Id', 'left')
					->order_by('er.emp_record_id','desc');  
            $recordsQ = $this->db->get(); 
		
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
					->order_by('er.emp_record_id','desc');
			
			 $recordsQ = $this->db->get(); 
			
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
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			
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
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
			
			
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
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get(); 
		   
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
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get();
		   
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
            ->order_by('er.emp_record_id','desc');
			
			$recordsQ = $this->db->get();
		
		endif;
	/********************** Search From Data And Export Execl Data *******************************************************/	
   
  
  //$query =$this->db->get('emp_record_details'); // fetch Data from table
  $allData = $recordsQ->result_array();  // this will return all data into array
				
               // $rs = $this->db->get('countries');
                $exceldata="";
				$sno = 0;
        foreach ($allData as $row){ $sno++;
		$getListOfProjects   	= $this->emptimelog_model->getAddedReportTaskNames($row['task_Id']);
			$arrangeData['Sno'] 	  = $sno;
			$arrangeData['Employee Name'] 	  = $row['name'];
			$arrangeData['Client Name'] 	  = $row['client_name'];
			$arrangeData['Project Name']	  = $row['project_name'];
			$arrangeData['Task Name'] 		  = $getListOfProjects;
			$arrangeData['Task Hours']		  = $row['emp_time_hours'];
			$arrangeData['Added Date'] 		  = $row['emp_report_dates'];
			$arrangeData['comments'] 		  = $row['comments'];
	
                $exceldata[] = $arrangeData;
        }
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
                 
                $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->excel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                 //$time = time();
                $filename="Employee_Report_sheet.xls"; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	
	
}
