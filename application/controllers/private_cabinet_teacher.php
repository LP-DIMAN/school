<?php
class Private_cabinet_teacher extends CI_Controller{
	public $title="Личный кабинет учителя";


function index(){

$this->load->model('m_users');

// Очистка старых сессий.
$this->m_users->ClearSessions();


// Текущий пользователь.
$user = $this->m_users->Get();

// Если пользователь не зарегистрирован - отправляем на страницу регистрации.
if ($user == null)
{
header("Location: registration");
	die();
}

// Может ли пользователь смотреть страницу?
if (!$this->m_users->Can('TEACHER'))
{
	redirect('login');
}


	   $this->form_validation->set_rules('surname', 'surname', 'trim|required|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('name', 'name', 'trim|required|min_length[2]|max_length[50]');
		$this->form_validation->set_rules('patronymic', 'patronymic', 'required|min_length[5]|max_length[50]');
		$this->form_validation->set_rules('login', 'email', 'required|valid_email');
		$this->form_validation->set_rules('actual_password', 'password', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('new_password', 'new_password', 'trim|required|min_length[5]|max_length[50]'); 
	       		
		

if ($this->form_validation->run() == FALSE)
{

	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_footer');


	$data['user']=$this->m_users->view_teacher_pupil();
		$this->load->view('v_private_cabinet_teacher',$data);
}
	else
			$this->m_users->update_teacher();
			








}



}

