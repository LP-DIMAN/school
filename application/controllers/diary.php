<?php

class Diary extends CI_Controller{


public $title="Дневник";
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
	



	$data['get_diary'] = $this->m_users->get_all_diary();//var_dump($data['view_access']);die();
	$this->load->view('v_header');
	$this->load->view('v_pupil');
	$this->load->view('v_diary',$data);
	$this->load->view('v_footer_pupil');

	




	}






	}
