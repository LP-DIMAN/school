<?php
class Teacher extends CI_Controller{
	public $title="Учитель";
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
if (!$this->m_users->Can('TEACHER'))
{
	redirect('login');
}

$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_footer');

}



}

