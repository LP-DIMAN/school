<?php
class Pupil extends CI_Controller{
	private $title="Личный кабинет ученика";
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
{
	redirect('login');

}
$this->load->view('v_header');
$this->load->view('v_pupil');
$this->load->view('v_footer_pupil');

}

}

