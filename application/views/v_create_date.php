<div class="table-responsive">
<h1>Добавить дату занятия</h1>
   
	<?php echo validation_errors(); ?>
<?php echo form_open("create_date?id=$id&obj=$obj"); ?>
<?php foreach ($interval as $date):?>
	

 <input type="date" name="date" max="<?=$date['date_end'];?>" min="<?=$date['date_start'];?>" value="<?=$date['date_start'];?>">
 <input type="submit"  name="add" class="btn btn-success"  value="Добавить">
<?php endforeach;?>
</form>
</div>