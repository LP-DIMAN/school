<?php
class Create_journal extends CI_Controller{

	public $title="Создание журнала";
	function index()  {

		$this->load->model('m_users');
		$this->m_users->set_rules_create_journal();

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
		if (!$this->m_users->Can('TEACHER') AND !$this->m_users->Can('CREATE_JOURNAL'))
		{
			redirect('login');
		}
		$this->db->distinct();
		 $this->db->select('id_class');
		$data['class']=$this->db->get('users');


    
        $this->db->where('id_user!=',$user[0]);
        $this->db->where('id_role=1');
        $data['teacher']=$this->db->get('users');


        if ($this->form_validation->run() == FALSE){

		$this->load->view('v_header');

		$this->load->view('v_teacher');
		$this->load->view('v_create_journal',$data);
		$this->load->view('v_footer');
       
     }
     else if($this->input->post('start') >= $this->input->post('end')){
     		 die("Дата начала ведения журнала больше или равно дате окончания!!!!");
     		}
     		else{
  $this->m_users->create_journal();
}




}



}