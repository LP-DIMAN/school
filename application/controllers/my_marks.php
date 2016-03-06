<?php

class My_marks extends CI_Controller{


public $title="Мои оценки";
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
	if (!$this->m_users->Can('PUPIL'))
		redirect('login');
	
$id=$this->input->get('id');
$obj=$this->input->get('obj');



	$data['my_marks'] = $this->m_users->get_my_marks($id,$obj);//var_dump($data['view_access']);die();
	$data['diary'] = $this->m_users->get_all_diary();
	$data['avg_mark'] = $this->m_users->get_avg_mark_pupil($id,$obj);
	$data['omissions'] = $this->m_users->get_count_omissions_pupil($id,$obj);
	$this->load->view('v_header');
	$this->load->view('v_pupil');
	$this->load->view('v_my_marks',$data);
	$this->load->view('v_footer_pupil');

	




	}






	}
