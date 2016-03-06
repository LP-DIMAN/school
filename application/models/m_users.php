<?php

//
// Менеджер пользователей
//
class M_Users extends CI_Model
{	
    private static $instance;	// экземпляр класса
	private $msql;				// драйвер БД
	private $sid;				// идентификатор текущей сессии
	private $uid;				// идентификатор текущего пользователя
	private $onlineMap;			// карта пользователей online

	//
	// Получение экземпляра класса
	// результат	- экземпляр класса MSQL
	//
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new M_Users();
			
		return self::$instance;
	}

	//
	// Конструктор
	//
	public function __construct()
	{
		parent::__construct();
	
		$this->load->helper(array('form', 'url','date'));

		$this->load->library(array('form_validation','session','table'));
		$this->sid = null;
		$this->uid = null;
		$this->onlineMap = null;
	}
	
	//
	// Очистка неиспользуемых сессий
	// 
	public function ClearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - 60 * 20); 			
		$t = "time_last < '$min'";
	    $this->db->where($t);
		$this->db->delete('sessions');
	}

	//
	// Авторизация
	// $login 		- логин
	// $password 	- пароль
	// $remember 	- нужно ли запомнить в куках
	// результат	- true или false
	//
	public function Login($login, $password, $remember = true)
	{
		// вытаскиваем пользователя из БД 
		$user = $this->GetByLogin($login);

		if ($user == null)
			return false;
		
		$id_user = $user[0];
				
		// проверяем пароль
		if ($user[5] !=md5($password))
			return false;
				
		// запоминаем имя и md5(пароль)
		
		if ($remember)
		{
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $login, $expire);
			setcookie('password', md5($password), $expire);
		}		

				
		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($id_user);
		
		//return true;
			if($user[7]==1)
				header("Location:journal");
			else 
				header("Location:diary");
			
	}
	
	//
	// Выход
	//
	public function Logout()
	{
		setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);		
		$this->sid = null;
		$this->uid = null;
		
	}
						
	//
	// Получение пользователя
	// $id_user		- если не указан, брать текущего
	// результат	- объект пользователя
	//
	public function Get($id_user = null)
	{	
		// Если id_user не указан, берем его по текущей сессии.
		if ($id_user == null)
			$id_user = $this->GetUid();
			
		if ($id_user == null)
			return null;
			
		// А теперь просто возвращаем пользователя по id_user.
	
		$this->db->where('id_user',$id_user);

		$result = $this->db->get('users');
		$res=array();
		foreach ($result->result_array() as $row) {
			$res[]=$row['id_user'];
			$res[]=$row['name'];
			$res[]=$row['surname'];
			$res[]=$row['patronymic'];
			$res[]=$row['login'];
			$res[]=$row['password'];
			$res[]=$row['id_class'];
			$res[]=$row['id_role'];

		}
		return $res;		
	}
	
	//
	// Получает пользователя по логину
	//
	public function GetByLogin($login)
	{	

		$this->db->where('login',$login);
		
		$result=$this->db->get('users');
		$res=array();
		foreach ($result->result_array() as $row) {
			$res[]=$row['id_user'];
			$res[]=$row['name'];
			$res[]=$row['surname'];
			$res[]=$row['patronymic'];
			$res[]=$row['login'];
			$res[]=$row['password'];
			$res[]=$row['id_class'];
			$res[]=$row['id_role'];

		}
	  return $res;
	}
			
	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function Can($priv, $id_user = null)
	{		
		$a='privs2roles.id_priv';
$b='privs.id_priv';
		$id_user=$this->Get();
$id=$id_user[0];
$id=intval($id);

		if ($id_user == null)
			return false;
         $result=$this->db->query("SELECT * FROM users NATURAL JOIN privs2roles INNER JOIN privs ON $a = $b
          WHERE id_user = '$id' and description='$priv'");
         foreach ($result->result() as $row) {
         	return $row->description;
         }
       
	}

	//
	// Проверка активности пользователя
	// $id_user		- идентификатор
	// результат	- true если online
	//
	public function IsOnline($id_user)
	{		
		if ($this->onlineMap == null)
		{	    
		    $t = "SELECT DISTINCT id_user FROM sessions";		
		    $query  = sprintf($t, $id_user);
		    $result = $this->msql->Select($query);
		    
		    foreach ($result as $item)
		    	$this->onlineMap[$item['id_user']] = true;		    
		}
		
		return ($this->onlineMap[$id_user] != null);
	}
	
	//
	// Получение id текущего пользователя
	// результат	- UID
	//
	public function GetUid()
	{	
		// Проверка кеша.
		if ($this->uid != null)
			return $this->uid;	

		// Берем по текущей сессии.
		$sid = $this->GetSid();
				
		if ($sid == null)
			return null;
	
		$this->db->where('sid',$sid);
		
		$result = $this->db->get('sessions');
			
		// Если сессию не нашли - значит пользователь не авторизован.
		if (count($result) == 0)
			return null;
		$res=array();
			foreach ($result->result_array() as $row) {
		   $res[]=$row['id_session'];
			$res[]=$row['id_user'];
			$res[]=$row['sid'];
			$res[]=$row['time_start'];
			$res[]=$row['time_last'];
			

			}
			
		// Если нашли - запоминм ее.
		$this->uid = $res[1];
		return $this->uid;
	}

	//
	// Функция возвращает идентификатор текущей сессии
	// результат	- SID
	//
	private function GetSid()
	{	
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
	
		// Ищем SID в сессии.
		$sid = $this->session->userdata('sid');;
								
		// Если нашли, попробуем обновить time_last в базе. 
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null)
		{
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			
			$this->db->where('sid',$sid);

			
			$affected_rows = $this->db->update('sessions', $session);

			if ($affected_rows == 0)
			{
				$result=$this->db->query("SELECT count(*) FROM sessions WHERE sid = '$sid'");		
				
				if ($result == 0)
					$sid = null;			
			}			
		}		
		
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if ($sid == null && isset($_COOKIE['login']))
		{
			$user = $this->GetByLogin($_COOKIE['login']);
			
			if ($user != null && $user[5] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['id_user']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возвращаем, наконец, SID.
		return $sid;		
	}
	
	//
	// Открытие новой сессии
	// результат	- SID
	//
	private function OpenSession($id_user)
	{
		// генерируем SID
		$sid = $this->GenerateStr(10);
				
		// вставляем SID в БД
		$now = date('Y-m-d H:i:s'); 
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;				
		$this->db->insert('sessions', $session); 
				
		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;				
				
		// возвращаем SID
		return $sid;	
	}

	//
	// Генерация случайной последовательности
	// $length 		- ее длина
	// результат	- случайная строка
	//
	private function GenerateStr($length = 10) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}




//////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////

			public function set_rules()
			{

				$this->form_validation->set_rules('surname', 'Фамилия', 'trim|required|min_length[5]|max_length[50]');
				$this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[50]');
				$this->form_validation->set_rules('patronymic', 'Отчество', 'required|min_length[5]|max_length[50]');
				$this->form_validation->set_rules('login', 'email', 'required|valid_email|is_unique[users.login]');
				$this->form_validation->set_rules('password', 'Пароль', 'trim|required|matches[confirm_password]|min_length[5]|max_length[50]');
				$this->form_validation->set_rules('confirm_password', 'Подтверждение пароля', 'trim|required|min_length[5]|max_length[50]');
		        $this->form_validation->set_rules('teacher', 'teacher');
				$this->form_validation->set_rules('class', 'class');

			}
			public function set_rules_create_journal()
			{

				$this->form_validation->set_rules('title', 'Название журнала', 'required|is_unique[journals.title]|min_length[2]|max_length[50]');
				$this->form_validation->set_rules('class', 'Номер учебного класса', 'required|is_unique[journals.id_class]');
				$this->form_validation->set_rules('start', 'Начало введенния журнала', 'required');
				$this->form_validation->set_rules('end', 'Окончание введения журнала', 'required');
				$this->form_validation->set_rules('id_object[]', 'Предметы', 'required');
				

			}


			public function check_date($id,$obj){
				$this->db->where('id_journal',$id);
				$this->db->where('id_object',$obj);
				$result = $this->db->get('date_lessons')->result_array();
				$arr=array();
				foreach ($result as $key) {
					$arr[] = $key['date'];
				}
				
				return $arr;

			}

			public function get_interval_journal($id){
				$this->db->where('id_journal',$id);
				$result = $this->db->get('journals')->result_array();

				return $result;



			}


			function  get_object(){

			$result=$this->db->get('objects');

		}
		function view_teacher_pupil(){
			$user = $this->m_users->Get();

		$this->db->where('id_user',$user[0]);
		$result=$this->db->get('users');
		$res=array();
		foreach ($result->result_array() as $key) {
		$res[] = $key['surname'];
		$res[] = $key['name'];
		$res[] = $key['patronymic'];
		$res[]= $key['login'];
		$res[]= $key['password'];

		}
		return $res;
		}

		function update_teacher()
		{
			
				$password=$this->m_users->view_teacher_pupil();
			$id_user=$this->m_users->Get();
			$data=array(
		         'surname'=>$this->input->post('surname'),
		         'name'=>$this->input->post('name'),
		          'patronymic'=>$this->input->post('patronymic'),
		          'login'=>$this->input->post('login'),
		          'password'=>md5($this->input->post('new_password'))

				);

				if($this->input->post('update')){
				if (md5($_POST['actual_password'])===$password['4']){

				$this->db->where('id_user',$id_user[0]);
				$this->db->update('users',$data);
				echo "<div style='color:red;'>Данные успешно обновлены</div>

					<a href='journal' style='color:#2c3e50'>К журналу</a>


					";

		}
			else
	echo "<div style='color:red;'>Вы ввели неверный текущий пароль</div>
			
			<a href='private_cabinet_teacher' style='color:#2c3e50'>К журналу</a>




	";
		}

		}

		function update_pupil()
		{
			
				$password=$this->m_users->view_teacher_pupil();
			$id_user=$this->m_users->Get();
			$data=array(
		         'id_class'=>$this->input->post('class'),
		         'password'=>md5($this->input->post('new_password'))

				);

				if($this->input->post('update')){
				if (md5($_POST['actual_password'])===$password['4']){
		          
				$this->db->where('id_user',$id_user[0]);
				$this->db->update('users',$data);
					echo "<div style='color:red;'>Данные успешно обновлены</div>

					<a href='diary' style='color:#2c3e50'>К дневнику</a>


					";
				//redirect('private_cabinet_pupil');

		}
			else
		echo "<div style='color:red;'>Вы ввели неверный текущий пароль</div>
			
			<a href='private_cabinet_pupil' style='color:#2c3e50'>К дневнику</a>




	";
		}

		}

			public function create_journal()
			{
				$id_user=$this->m_users->Get();
			
				$data=array(
				 'id_teacher'=>$id_user[0],
		         'title'=>$this->input->post('title'),
		         'id_class'=>$this->input->post('class'),
		         'date_start'=>$this->input->post('start'),
		         'date_end'=>$this->input->post('end'),
		         'create_date' => date("Y-m-d H:i:s", time()));
		         


		      
				
				
				if ($this->input->post('create')){
					$this->db->insert('journals',$data);
					
					$this->db->select('id_journal');
					$this->db->where('title',$data['title']);
					$result=$this->db->get('journals')->row()->id_journal;
					
					for($i=0;$i<count($this->input->post('teacher[]'));$i++){
						$data1=array('id_journal'=>$result,
							'id_teacher'=>$this->input->post("teacher[$i]"));
						$this->db->insert('teacher_access',$data1);
					}
		               for($i=0;$i<count($this->input->post('id_object[]'));$i++){
						$data2=array('id_journal'=>$result,
							'id_object'=>$this->input->post("id_object[$i]"));
						$this->db->insert('journal_object',$data2);
					}
					header("Location:journal");
				}


			}

	public function get_journal($id){
		        $id_user=$this->m_users->Get();
				$this->db->where('id_teacher',$id_user[0]);
			      $this->db->where('id_journal',$id);
				$result=$this->db->get('journals')->result_array();
				return $result;
	}

	public function edit_access_journal($id)
	{
        
			$this->db->where('id_journal',$id);
			$result=$this->db->get('journals')->result_array();
				return $result;
	}

		public function get_all_journal()
		{

			$id_user=$this->m_users->Get();
			$this->db->where('id_teacher',$id_user[0]);
			$this->db->order_by('create_date','desc');
			$result=$this->db->get('journals')->result_array();
			return $result;
		}

		public function delete_journal($id)
		{
			
      
			$id_user = $this->m_users->Get();
			
			$this->db->delete('journals', array('id_teacher' => $id_user[0],'id_journal' => $id));
			$this->db->delete('journal_object', array('id_journal' => $id));
			$this->db->delete('teacher_access', array('id_journal' => $id));
			$this->db->delete('view_journal', array('id_journal' => $id));
			$this->db->delete('teacher_access', array('id_journal' => $id));
			$this->db->delete('date_lessons', array('id_journal' => $id));


			

		
		}
        
   		public function get_access_teacher()
   		{
   			$id_user = $this->m_users->Get();
   			$this->db->where('id_teacher',$id_user[0]);
   			$result = $this->db->get('journals')->result_array();
   		
   			if($result==null)
   				return false;
   		}



		public function get_access_journal()
		{
		$id_user = $this->m_users->Get();
		$this->db->select('id_journal');
		$this->db->where('id_teacher',$id_user[0]);
		$result = $this->db->get('teacher_access');
		return $result->result_array();
		}

		public function view_access_journal()
		{
            
             
			$id_user = $this->m_users->Get();

			$this->db->order_by('create_date','desc');
			$result = $this->db->query("SELECT * from teacher_access as t inner join journals as j on t.id_journal=j.id_journal where not j.id_teacher='$id_user[0]'")->result_array();
			
			return $result;	
		}


	    public function objects_pupils($id){

	     	$result=$this->db->query("SELECT * from journal_object natural join objects where id_journal='$id'");
	     	return $result->result_array(); 
	     }

          public function get_pupils($id)
          {

          	$result=$this->db->query("SELECT * FROM users  inner join journals on users.id_class=journals.id_class   where id_journal='$id' order by surname asc");
          	return $result->result_array();
          }

          public function get_objects_from_journal($id)
          {

          	$result=$this->db->query("SELECT * FROM journal_object natural join objects where id_journal='$id'");
          	return $result->result_array();
          }

          public function get_objects_from_journal_by_id($id,$obj)
          {

          	$result=$this->db->query("SELECT * FROM journal_object natural join objects where id_journal='$id' and id_object='$obj'");
          	return $result->result_array();
          }


          public function create_date_lesson($id,$obj){
          	$date = $this->m_users->check_date($id,$obj);
            $id_user= $this->m_users->Get();
            $data=array(
            		'id_teacher'=>$id_user[0],
            		'id_object'=> $obj,
            		'id_journal'=>$id,
            		'date'=>$this->input->post('date'));
            		for($i=0;$i<count($date);$i++){
            			if ($this->input->post('date')==$date[$i])
            				die('Такая  дата для этого предмета существует');
            		}


          $this->db->insert('date_lessons',$data);
          }
	     

           public function get_date_lesson($id,$obj){

           	$id_user= $this->m_users->Get();

           	$this->db->where('id_journal',$id);
           	$this->db->where('id_object',$obj);
           	$this->db->order_by('date','asc');
           	$result = $this->db->get('date_lessons');
           	return $result->result_array();

           }
           public function get_date_lesson_by_id($id){
               	$id_user= $this->m_users->Get();
             $this->db->where('newid',$id);
             //$this->db->where('id_teacher',$id_user[0]);
             $result=$this->db->get('date_lessons');
             return $result->result_array();

           }
           public function insert_rating($id,$dateid,$obj){
           	$id_user = $this->m_users->Get();

	       		$result = $this->get_pupils($id);
	           	$pupils = array();
	           	foreach ($result as $pupil) 
           		$pupils[] = $pupil['id_user'];
           		
        
           	for($i=0,$j=0;$i<count($pupils),$j<count($this->input->post('rating[]'));$i++,$j++){
           		
					if ($this->input->post("rating[$j]")=='н'){
						$data1=array(
           		'id_teacher'=>$id_user[0],
           		'id_pupil'=>$pupils[$i],
           		'id_journal'=>$id,
           		'id_object'=>$obj,
           		'date'=>$dateid,
           		'rating'=>$this->input->post("rating[$j]"),
           		'omission'=>$this->input->post("rating[$j]"));

						$this->db->insert('view_journal',$data1);
					}
					elseif($this->input->post("rating[$j]")=='-'){
					
					$data2=array(
           		'id_teacher'=>$id_user[0],
           		'id_pupil'=>$pupils[$i],
           		'id_journal'=>$id,
           		'id_object'=>$obj,
           		'date'=>$dateid,
           		'rating'=>$this->input->post("rating[$j]"),
           		'no_mark'=>$this->input->post("rating[$j]"));


						$this->db->insert('view_journal',$data2);								

					}

					else{
				$data=array(
           		'id_teacher'=>$id_user[0],
           		'id_pupil'=>$pupils[$i],
           		'id_journal'=>$id,
           		'id_object'=>$obj,
           		'date'=>$dateid,
           		'rating'=>$this->input->post("rating[$j]"),
           		'marks'=>$this->input->post("rating[$j]")	
           		);

						$this->db->insert('view_journal',$data);
					
					}
					}
           	
           }
          

           public function edit_rating($id,$dateid,$obj){
           	$id_user = $this->m_users->Get();

	       		$result = $this->get_pupils($id);
	           	$pupils = array();
	           	foreach ($result as $pupil) {
           		$pupils[] = $pupil['id_user'];
           	};

        
           	for($i=0;$i<count($pupils),$i<count($this->input->post('rating[]'));$i++){

					
			

						$this->db->where('id_journal',$id);
						$this->db->where('id_object',$obj);
						$this->db->where('id_pupil',$pupils[$i]);
						$this->db->where('date',$dateid);
						//$this->db->where('id_teacher',$id_user[0]);
						if ($this->input->post("rating[$i]")=='н'){
						$this->db->set('rating',$this->input->post("rating[$i]"));
							$this->db->set('omission',$this->input->post("rating[$i]"));
							$this->db->update('view_journal'); 
						}
						elseif ($this->input->post("rating[$i]")=='-'){
							$this->db->set('rating',$this->input->post("rating[$i]"));
								$this->db->set('no_mark',$this->input->post("rating[$i]"));
								$this->db->update('view_journal'); 
							}
							else{
								$this->db->set('rating',$this->input->post("rating[$i]"));
								$this->db->set('marks',$this->input->post("rating[$i]"));
						$this->db->update('view_journal'); 
					}
						
					
					}
           }

           public function view_rating_for_update($id,$dateid,$obj)
           {	
           		$this->db->where('id_journal',$id);
           		$this->db->where('date',$dateid);
           		$this->db->where('id_object',$obj);
           		$result = $this->db->get('view_journal')->result_array();
           
           		return $result;
           }
           
           public function get_all($id,$obj)
           {
           	$result=$this->db->query("SELECT d.newid, d.id_teacher, d.id_object, d.id_journal, d.date, v.id_pupil, v.rating
FROM date_lessons AS d
INNER JOIN view_journal AS v ON d.date = v.date
WHERE v.id_journal =  '$id'
AND v.id_object =  '$obj'");
       
           	return $result->result_array();
           }

		
           public function get_rating($id,$obj){

       
           	$this->db->where('id_journal',$id);
           	$this->db->where('id_object',$obj);

           	$marks = $this->db->get('view_journal');
         	$lessons = array();
         	foreach ($marks->result_array() as $mark) {
         		if(!isset($lessons[$mark['date']])){
         			$lessons[$mark['date']]=array();
         		}
         	$lessons[$mark['date']][$mark['id_pupil']]=$mark;
         }
         
        return $lessons;
           		
          
           


           }

          public function get_pupils_rating($id)
          {
          	$result=$this->db->query("SELECT users.surname,users.name,users.patronymic,view_journal.rating FROM users  inner join view_journal on users.id_user=view_journal.id_pupil   where id_journal='$id' and date='2015-08-29'  order by surname asc");
          	return $result->result_array();
          }

 			public function get_avg_mark($id,$obj)
          {
          
          	$result = $this->db->query("SELECT round(avg(marks),1) from view_journal where id_journal='$id' and id_object='$obj' group by id_pupil order by newid asc")->result_array();
          	return $result;

          }

          public function get_count_omission($id,$obj)
          {
          	$this->db->select('count(omission)');
          	$this->db->group_by('id_pupil');
          	$this->db->order_by('newid','asc');
          	$this->db->where('id_journal',$id);
          	//$this->db->where('rating','н');
          	$this->db->where('id_object',$obj);
          	$result = $this->db->get('view_journal')->result_array();
         
          	return $result;


          }

          /////////////////////////////////////pupil////////////////////////////////////////////////////////
          public function get_all_diary()
          {
          	$id_user=$this->m_users->Get();
			$result=$this->db->query("SELECT * from users inner join journals where users.id_user='$id_user[0]' and users.id_class=journals.id_class")->result_array();
			return $result;
          }
           public function get_objects_from_diary($id)
          {

          	$result=$this->db->query("SELECT * FROM journal_object natural join objects where id_journal='$id'");
          	return $result->result_array();
          }
           public function get_my_marks($id,$obj)
          {
          	$id_user=$this->m_users->Get();
          	$this->db->where('id_pupil',$id_user[0]);
          	$this->db->where('id_journal',$id);
          	$this->db->where('id_object',$obj);
          	$result = $this->db->get('view_journal');
          	return $result->result_array();

          	
          }

         	public function get_avg_mark_pupil($id,$obj)
         	{
         		$id_user = $this->m_users->Get();
         		$this->db->select_avg('rating');
         		$this->db->where('rating !=','н');
         		$this->db->where('rating !=','-');
         		$this->db->where('id_pupil',$id_user[0]);
         		$this->db->where('id_object',$obj);
         		$result = $this->db->get('view_journal')->result_array();
         		return $result;
         	}
         	public function get_count_omissions_pupil($id,$obj)
         	{
         		$id_user = $this->m_users->Get();
         		$this->db->select('count(rating)');
         		$this->db->where('rating','н');
         		$this->db->where('id_object',$obj);
         		$this->db->where('id_pupil',$id_user[0]);
         		$result = $this->db->get('view_journal')->result_array();
         		return $result;
         	}

}

