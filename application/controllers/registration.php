<?php
	class Registration extends CI_Controller{
		public $title="Регистрация";
		public $surname;
	public $name;
	public $patronymic;
	public $login;
	public $confirm_password;
	public $id_class;
			function index(){
	 $this->load->model('m_users');
	$this->m_users->set_rules();
    $this->surname=$this->input->post('surname');
	$this->name=$this->input->post('name');
	$this->patronymic=$this->input->post('patronymic');
	$this->login=$this->input->post('login');
	$this->password=$this->input->post('password');
	$this->confirm_password=$this->input->post('confirm_password');
	$this->id_class=$this->input->post('id_class');

	$data=array(

	'name'=>$this->name,
	'surname'=>$this->surname,
	'patronymic'=>$this->patronymic,
	'login'=>$this->login,
	'password'=>md5($this->confirm_password),
	'id_role'=>1,
	'create_date' => date("Y-m-d H:i:s", time()));


	$data1=array(

	'name'=>$this->name,
	'surname'=>$this->surname,
	'patronymic'=>$this->patronymic,
	'login'=>$this->login,
	'password'=>md5($this->confirm_password),
	'id_class'=>$this->id_class,
	'id_role'=>2,
	'create_date' => date("Y-m-d H:i:s", time()));




		       if ($this->form_validation->run() == FALSE)
		       {
		       		$this->load->view("v_header");
			    	$this->load->view('v_registration');
			    	$this->load->view('v_footer');
			}

		
			  else
			  	{

			if($this->input->post('teacher')==1)
			{

		
		
	               $this->db->insert('users', $data); 
			
				$this->load->view('v_header');
				$this->load->view('form_success');
			}

					elseif($this->input->post('teacher')==2)
					{
		     			 $this->db->insert('users', $data1); 
		     			 $this->load->view('v_header');
						$this->load->view('form_success');

					}
					else{
						echo "ВЫ не зарегестрировались. Возможно, вы заполнили не все поля<br>

						<a href='registration'>Регистрация</a>

						";
					}
					}
			
			
		}

	}





