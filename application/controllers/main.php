<?php
class Main extends CI_Controller{
     public $title="Главная";


     function __construct(){
     	
     		parent::__construct();
     		$this->load->model('m_users');
	         $this->m_users->Logout();

     }
function index(){
	
	$this->load->model('m_users');

	$this->load->view('v_main');
}


}
    
    


