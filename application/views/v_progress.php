<div class="table-responsive">
<?php foreach ($obj as $object):?>
	<h1><?=$object['name'];?></h1>
<?php endforeach;?>

 <form action="" method="post">
  
 <table class="table" >
<tr>
 <td>
  <table class="table" >
  <tr>
  	<td>ФИО</td>
 
	    	
  	</tr>
 
  <?php foreach ($get_pupils as $pupil):?>
		 	<tr>
		<td>
			<?echo $pupil['surname']."&nbsp;"; echo $pupil['name']."&nbsp;"; echo $pupil['patronymic'];?>
		
		</td>
</tr>
<?php endforeach ;?>
</table>
</td>





<?php foreach ($get_rating as $rating=>$mark):?>
		<td>

<table class="table">
			<td align="center" style="font-size:13px;"><?php echo"$rating";?></td>
<?php foreach ($mark as $key):?>
	
		<tr><td align="center"><strong><?=$key['rating'];?></strong></td></tr>
		

	<?php endforeach;?>
	</table>
	</td>
<?php endforeach;?>
	 

<td>
	<table class="table">
	<tr><td style="font-size:13px;">Средняя оценка</td></tr>
		<?php foreach ($avg_mark as $mark) :?>
			<?php if($mark['round(avg(marks),1)']==null) :?>
				<tr>
			<td ><strong>0</strong></td>
				</tr>
			<?php endif;?>
			<tr>
			<td ><strong><?=$mark['round(avg(marks),1)'];?></strong></td>
				</tr>
		<?php endforeach;?>

</table>

</td>

<td>
	<table class="table">
	<tr><td style="font-size:13px;">Количество пропусков</td></tr>
	<?php foreach ($omission as $count) :?>
			<tr>
			<td><strong><?=$count['count(omission)'];?></strong></td>
				</tr>
		<?php endforeach;?>	

</table>
</td>

</tr>
<div><a href="create_date?id=<?=$pupil['id_journal'];?>&obj=<?=$obj_id;?>" class="glyphicon glyphicon-plus">Добавить дату занятия</a></div><br>
<div>
<?php foreach($get_date_lesson as $lesson):?>
	    <?=$lesson['date'];?> <a href="edit_rating?id=<?=$lesson['id_journal'];?>&dateid=<?=$lesson['newid'];?>&obj=<?=$obj_id;?>" class="glyphicon glyphicon-pencil" > </a> |
<?php endforeach;?>
</div>
<div style="height:25px;"></div>
</table>

</form>
</div>