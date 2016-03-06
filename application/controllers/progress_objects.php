<?php

class Progress_objects extends CI_Controller{


public $title="Успеваемость";
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
  $id = $this->input->get('id');
//$data['get_pupils'] = $this->m_users->get_pupils($id);
//$data['get_date_lesson'] = $this->m_users->get_date_lesson();
$data['get_objects_from_journal'] = $this->m_users->get_objects_from_journal($id);

$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_progress_objects',$data);
$this->load->view('v_footer');





}






}

