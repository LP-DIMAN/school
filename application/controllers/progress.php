<?php

class Progress extends CI_Controller{


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
  $obj =  $this->input->get('obj');
  $data['obj_id'] = $this->input->get('obj');


	$data['get_pupils'] = $this->m_users->get_pupils($id);
	$data['get_date_lesson'] = $this->m_users->get_date_lesson($id,$obj);
	$data['obj'] = $this->m_users->get_objects_from_journal_by_id($id,$obj);
	$data['get_rating'] = $this->m_users->get_rating($id,$obj);
	$data['avg_mark'] = $this->m_users->get_avg_mark($id,$obj);
	$data['omission'] = $this->m_users->get_count_omission($id,$obj);

$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_progress',$data);
$this->load->view('v_footer');





}






}

