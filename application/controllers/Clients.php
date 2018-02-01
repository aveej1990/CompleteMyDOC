<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

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
		
		if(empty($this->session->userdata['logged_in_timesheet'])){
		
			redirect('home/login');
		}
		
		
    }
	
	public function index(){
		
			$data['getClients'] = $this->client_model->getClients();
			
			$this->load->view('clients/clients' , $data);
			
	}
	
	public function add($client_Id = NULL){
	
	   if(empty($client_Id)) : 
			
			 $this->load->view('clients/add_client');
			 
		else:
			
			 $data['updateClient'] = $this->client_model->getClients($client_Id);
	
		     $this->load->view('clients/add_client' , $data);
					
		endif;	   
			
	 
	}
	
	public function addclient(){ // Adding new Client function. 	
			
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('client_name', 'Client name already exit. Please try another name', 'required|trim|is_unique[client_details.client_name]');
		
		//$this->form_validation->set_rules('client_email', 'Client email already exit. Please try another email', 'required|trim|is_unique[client_details.client_email]');
		
		if ($this->form_validation->run() == FALSE) {
	
			$this->load->view('clients/add_client');
			
	    }else{
						
			$data = array(
			'client_name' 				 => $this->input->post('client_name'),
			'client_email' 				 => $this->input->post('client_email'),
			'client_contact_num' 		 => $this->input->post('client_contact_num'),
			'client_desc'				 => $this->input->post('client_desc'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'created_at'    			 => date('Y-m-d H:i:s'),
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->client_model->add_client($data);
		
		 redirect('clients');
		
	   }
	
	}
	
	
	public function updateclient(){ // Adding new Client function. 	
			
		$client_Id = $this->input->post('client_Id');
		
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	
	    $this->form_validation->set_rules('client_name', 'Client name already exit. Please try another name', 'required|trim|callback_exists_clients');
		
		if ($this->form_validation->run() == FALSE) {
			
			$data = array(
			'client_desc'				 => $this->input->post('client_desc'),
			'client_contact_num' 		 => $this->input->post('client_contact_num'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	       
		   $this->load->view('clients/add_client');
		   
	       $this->client_model->update_client($data , $client_Id );
		  
		   redirect('clients');
		  
		   //redirect('clients/add/'.$client_Id);
			
	    }else{
						
			$data = array(
			'client_name' 				 => $this->input->post('client_name'),
			'client_email' 				 => $this->input->post('client_email'),
			'client_contact_num' 		 => $this->input->post('client_contact_num'),
			'client_desc'				 => $this->input->post('client_desc'),
			'empId'						 => $this->session->userdata['logged_in_timesheet']['empId'],
			'updated_at' 		 		 => date('Y-m-d H:i:s')
			);		
	
	     $this->client_model->update_client($data , $client_Id );
		
		 redirect('clients');
		
	   }
	
	}

  public function delete(){
  
        $client_Id  = $this->input->post('client_Id');
		   
			if(!empty($client_Id)):
			
				$del = $this->client_model->del_client($client_Id);
				
			endif;	
  
  }
  
 
  
  public function getRecentClients(){  //Get Recent Clients Angular js funciton  
   		
		$recentClientInfo = $this->client_model->recentClients();
		
		echo json_encode($recentClientInfo);
  
  }
	
  #uniqueness of task based on client and projects
    function exists_clients($str){ #uniqueness of Car Model
	
        $client_name = $this->input->post('client_name');
		
		$query = $this->db->get_where('client_details',array('client_name'=>$client_name));
        
		$countClients = $query->num_rows(); 
		
        if ($countClients  == 0){
		
            return TRUE;
			
        }else{
		
            $this->form_validation->set_message('exists_clients', 'Client name already exit. Please try another client!');
            
			 return FALSE;
        }
    }		


}
