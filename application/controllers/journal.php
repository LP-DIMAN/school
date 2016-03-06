<?php

class Journal extends CI_Controller{


public $title="Журналы";
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
	if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('JOURNAL'))
	{
		redirect('login');
	}


	$data['get_journal'] = $this->m_users->get_all_journal();
$data['view_access'] = $this->m_users->view_access_journal();
	$this->load->view('v_header');
	$this->load->view('v_teacher');
	$this->load->view('v_journal',$data);	
	$this->load->view('v_footer');





	}






	}

