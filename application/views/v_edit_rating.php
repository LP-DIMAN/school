<div class="table-responsive">
<? foreach ($date as $d):?> 
	<h1><? echo $d['date'];?></h1>
<?php endforeach;?>

   
 <form action="" method="post">
 <div>
 <input type="submit" name="save" class="btn btn-success" value="Сохранить"> &nbsp; &nbsp; &nbsp;
<input type="submit" name="edit" class="btn btn-warning" value="Обновить">
</div><br>
 <table class="table" >

 <tr class="danger">
 	<td>ФИО</td>
   <td>Оценка</td>
 </tr>
<?php foreach ($get_pupils as $pupil):?>
	<tr>
	<td>
	<?echo $pupil['surname']."&nbsp;"; echo $pupil['name']."&nbsp;"; echo $pupil['patronymic'];?>
	</td>
	<td>
		<select name="rating[]" >
		<option value="-">-</option>
		<option value="н">н</option>
		<?php for($i=1;$i<=5;$i++):?>
			<option value="<?=$i;?>"><?=$i;?></option>
		<?php endfor;?>
		</select>
	</td>
	<?php endforeach ;?>
	</tr>





  </table>
    
    </form>
</div>