<?php

class View_journal extends CI_Controller{


public $title="Просмотр журнала";
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
if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('VIEW_JOURNAL'))
{
	redirect('login');
}
  $id = $this->input->get('id');

	if ($this->m_users->get_access_teacher()!=true){ 

	$data['edit_access'] = $this->m_users->edit_access_journal($id);
	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_view_journal',$data);
	$this->load->view('v_footer');
}
else{
	$data['get_journal'] = $this->m_users->get_journal($id);
	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_view_journal',$data);	
}
	




}

}