<?php

class Create_date extends CI_Controller{


public $title="Добавление даты";
function index(){

$this->load->model('m_users');

// Очистка старых сессий.
$this->m_users->ClearSessions();

// Текущий пользователь.
$user = $this->m_users->Get();

// Если пользователь не зарегистрирован - отправляем на страницу регистрации.
if ($user == null)
{
header("Location: login");
	die();
}

// Может ли пользователь смотреть страницу?
	if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('VIEW_JOURNAL') AND !$this->m_users->Can('VIEW_PROGRESS'))
	{
		redirect('login');
	}
	
$this->form_validation->set_rules('date', 'Дата занятия', "required");

	  $id = $this->input->get('id');
$obj = $this->input->get('obj');
$data['id'] = $this->input->get('id');
$data['obj'] = $this->input->get('obj');
$data['interval'] = $this->m_users->get_interval_journal($id);
	if ($this->form_validation->run() == FALSE){
	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_create_date',$data);
	$this->load->view('v_footer');
	}
	else{
	if ($this->input->post('add')){
	$this->m_users->create_date_lesson($id,$obj);
    redirect("progress?id=$id&obj=$obj");
}
}





}






}

