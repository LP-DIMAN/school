<?php

class Omissions extends CI_Controller{


public $title="Пропуски";
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
if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('VIEW_JOURNAL') AND !$this->m_users->Can('VIEW_OMISSIONS'))
{
	redirect('login');
}
  $id = $this->input->get('id');
$data['get_pupils'] = $this->m_users->get_pupils($id);
$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_omissions',$data);
$this->load->view('v_footer');




}






}

