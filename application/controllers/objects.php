<?php

class Objects extends CI_Controller{


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
if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('VIEW_JOURNAL') AND !$this->m_users->Can('VIEW_OBJECTS'))
{
	redirect('login');
}
  $id = $this->input->get('id');
$data['objects_pupils'] = $this->m_users->objects_pupils($id);
$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_objects',$data);
$this->load->view('v_footer');




}






}

