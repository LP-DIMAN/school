
<div class="container">
<?php echo validation_errors(); ?>

<?php echo form_open('private_cabinet_teacher'); ?>

<p>
<label for="surname">Фамилия</label><br>
<input type="text" name="surname"  id="surname" value="<?=$user[0]?>"><br>
</p>
<p>
<label for="name">Имя</label><br>
<input type="text" name="name" id="name"  value="<?=$user[1]?>"><br>
</p>
<p>
<label for="patronymic">Отчество</label><br>
<input type="text" name="patronymic" id="patronymic"  value="<?=$user[2]?>"><br>
</p>
<p>
<label for="email">Email</label><br>
<input type="text" name="login" id="login"  value="<?=$user[3]?>"><br>
</p>

<p>

<label for="password">Текущий пароль</label><br>
<input type="password" name="actual_password"   value=""><br>
</p>


<p>

<label for="new_password">Новый пароль</label><br>
<input type="password" name="new_password" value=""><br>
</p>
<input type='submit' value="Обновить"name="update" class="btn btn-success" class="btn btn-primary btn-lg">
</p>

