<?php
class Login extends CI_Controller{
	public $title="Авторизация";
function index(){
	$this->load->model('m_users');
	$this->load->helper(array('form', 'url'));

	


// Очистка старых сессий.
$this->m_users->ClearSessions();

// Выход.
$this->m_users->Logout();

// Обработка отправки формы.

$this->m_users->Login($this->input->post('login'), 
	                   $this->input->post('password'), 
					   $this->input->post('remember'));
	




$this->load->view('v_header');
$this->load->view('v_login');
$this->load->view('v_footer');


}


}