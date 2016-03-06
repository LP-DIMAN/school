<?php
class Private_cabinet_pupil extends CI_Controller{
	public $title="Личный кабинет ученика";


function index(){

$this->load->model('m_users');

// Очистка старых сессий.
$this->m_users->ClearSessions();


// Текущий пользователь.
$user = $this->m_users->Get();

// Если пользователь не зарегистрирован - отправляем на страницу регистрации.
if ($user == null)
{
header("Location: registration");
	die();
}

// Может ли пользователь смотреть страницу?
if (!$this->m_users->Can('PUPIL'))
{
	redirect('login');
}


	  
		$this->form_validation->set_rules('login', 'email', 'required|valid_email');
        $this->form_validation->set_rules('actual_password', 'password', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('new_password', 'new_password', 'trim|required|min_length[5]|max_length[50]'); 
	       		
		

		if ($this->form_validation->run() == FALSE)
		{
			$this->db->distinct();
		$this->db->select('id_class');
		$data['res']=$this->db->get('users');

			$this->load->view('v_header');
			$this->load->view('v_pupil');
			$this->load->view('v_footer_pupil');
			$data['user']=$this->m_users->view_teacher_pupil();
				$this->load->view('v_private_cabinet_pupil',$data);
		}
			else
					$this->m_users->update_pupil();
					








		}



}

