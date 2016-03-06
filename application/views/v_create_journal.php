	<h2>Создание журнала </h2>
	<?php echo validation_errors(); ?>


<?php echo form_open('create_journal'); ?>
<p>
<label for="title">Название журнала</label><br>
<input type="text" name="title"  id="title" value=""><br>
</p>
<p>
<label for="class">Учебный класс</label><br>


<select name="class">
	
<?php foreach ($class->result() as $key):?>
    <? if ($key->id_class==null)
    continue;?>
		<option value="<?=$key->id_class;?>"><?=$key->id_class;?></option>
		
<?php endforeach;?>


</select>
	


</p>
<p>
<label for="start">Начало введения журнала</label><br>
<input type="date" name="start" id="start"  min="2015-10-15" value="2015-10-15"><br>
</p>
<p>
<label for="end">Окончание введения журнала</label><br>
<input type="date" name="end" id="end" min="2015-10-15" value="2015-10-15"><br>
</p>
<p>
 <label for="object">Добавить предметы</label><br>
 	<?

	$result=$this->db->get('objects');
	
 	?>
 <select name="id_object[]" multiple="yes">
 
<?php foreach ($result->result() as $row):?>
	<option value="<?=$row->id_object?>"><?php echo $row->name;?></option>

<?php endforeach;?>

 </select>
 </p>
<p>
<label for="access">Открыть доступ учителям</label><br>
<select name="teacher[]" id="teacher" multiple="yes">
<?php foreach ($teacher->result() as $fio_teacher) :?>
	
<option value="<?=$fio_teacher->id_user;?>"><? echo $fio_teacher->surname."&nbsp;";echo $fio_teacher->name; ?></option>

<?php endforeach;?>
	

</select>

</p>
<input type="submit" class="btn btn-default" value="Создать" name="create">

</form>