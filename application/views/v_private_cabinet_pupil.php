<div class="container">
	<?php echo validation_errors(); ?>

<?php echo form_open('private_cabinet_pupil'); ?>

<p>
<label for="email">Email</label><br>
<input type="text" name="login" id="login"  value="<?=$user[3]?>"><br>
</p>
<p>
<p>
<label for="class">Смена класса</label><br>
	<select name="class" id="class">
		<?php foreach ($res->result() as $key):?>
    <? if ($key->id_class==null)
    continue;?>
    
		<option value="<?=$key->id_class;?>"><?=$key->id_class;?></option>
		
<?php endforeach;?>
	</select>

</p>
<p>
<label for="actual_password">Текущий пароль</label><br>
<input type="password" name="actual_password" id ="actual_password"value=""><br>
</p>
<label for="new_password">Новый пароль</label><br>
<input type="password" name="new_password" id="new_password"  value=""><br><br>

<input type='submit' value="Обновить"name="update"class="btn btn-success" class="btn btn-primary btn-lg">
</p>

</div>