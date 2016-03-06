<?php

class Verification extends CI_Controller{


public $title="Подтверждение";
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
  $id = $this->input->get('id');

if ($this->form_validation->run() == FALSE){
	$this->load->view('v_header');
$this->load->view('v_teacher');
$this->load->view('v_verification');
$this->load->view('v_footer');


}
if ($this->input->post('yes')){

	$this->m_users->delete_journal($id);
redirect('journal');
}
else if ($this->input->post('no'))
	redirect('journal');



}






}

