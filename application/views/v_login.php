<?php echo validation_errors(); ?>
<?php echo form_open('login'); ?>
<p>

<label for="login">Логин(email)</label><br>
<input type="text" name="login" id="login"><br>
</p>
<p>
<label for="password">Пароль</label><br>
<input type="password" name="password" id="password"><br>
</p>
<p>

Запомнить меня <input type="checkbox" name="remember"><br>
</p>
<input type="submit" value="Вход" class="btn btn-success"><br>

</div>