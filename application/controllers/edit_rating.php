<?php

class Edit_rating extends CI_Controller{


public $title="Редактирование оценок";
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
	   $dateid = $this->input->get('dateid');

	     $obj = $this->input->get('obj');
	   	$data['date'] = $this->m_users->get_date_lesson_by_id($dateid);
	   	 $date =$this->m_users->get_date_lesson_by_id($dateid);
	
	$data['get_pupils'] = $this->m_users->get_pupils($id);
   $data['view'] = $this->m_users->view_rating_for_update($id,$date[0]['date'],$obj);
   

	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_edit_rating',$data);
	$this->load->view('v_footer');
	
if ($this->input->post('save')){
	$this->m_users->insert_rating($id,$date[0]['date'],$obj);
	redirect("progress?id=$id&obj=$obj");
	}
	
	if ($this->input->post('edit')){
	$this->m_users->edit_rating($id,$date[0]['date'],$obj);
	redirect("progress?id=$id&obj=$obj");
}

	




}






}

