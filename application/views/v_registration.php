	<h2>Регистрация</h2>
	<?php echo validation_errors(); ?>
<?php echo form_open('registration'); ?>
<p>
<label for="surname">Фамилия</label><br>
<input type="text" name="surname"  id="surname" value="<?=$this->input->post('surname');?>"><br>
</p>
<p>
<label for="name">Имя</label><br>
<input type="text" name="name" id="name"  value="<?=$this->input->post('name');?>"><br>
</p>
<p>
<label for="patronymic">Отчество</label><br>
<input type="text" name="patronymic" id="patronymic" value="<?=$this->input->post('patronymic');?>"><br>
</p>
<p>
<label for="login">Email</label><br>
 <input type="text" name="login" id="login" value="<?=$this->input->post('login');?>"><br>
 </p>
 <p>
 <label for="password">Пароль</label><br>
 <input type="password" name="password" id="password" ><br>
 </p>
 <p>
 <label for="confirm_password">Подтверждение пароля</label><br>
 <input type="password"  name="confirm_password" id="confirm_password"><br>
 </p>
<p>
Учитель<input type="radio" name="teacher" value="1">  Ученик <input type="radio" name="teacher" value="2"><br>
</p>
<p>
<label for="id_class"></label>
Номер учебного класса: 
<select name="id_class" id="id_class">
<?php for($i=1;$i<=11;$i++):?>


	<option value="<?=$i;?>-а">
		<?=$i;?>-а класс
	</option>
	<option value="<?=$i;?>-б">
		<?=$i;?>-б класс
	</option>
	<option value="<?=$i;?>-в">
		<?=$i;?>-в класс
	</option>

<?php endfor;?>
</select>

 *только для школьников<br>
 </p>

<input type="submit" class="btn btn-default" value="Зарегистрироваться">

</form>
</div>