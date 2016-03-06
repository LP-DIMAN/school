<div class="container">
<h1>Период:<?php foreach ($diary as $d):?>

<?php echo $d['date_start']."&nbsp;"; echo $d['date_end'];?>
<?php endforeach;?>
</h1>

<table class="table" >
<td>
<table class="table" >
<tr>

<?php foreach ($my_marks as $marks):?>


	<td><?php echo $marks['date'];?></td>
	<tr>
<td><?php echo "<strong>".$marks['rating']."</strong>";?></td>
</tr>

<?php endforeach;?>
</tr>
</table>
</td>
<td>
<table class="table" >
<tr>
	Средняя оценка:<br>
	<?php foreach ($avg_mark as $mark):?>
		<strong><?=$mark['rating'];?></strong>
	<?php endforeach;?>

</tr>




</table>
</td>

<td>
<table class="table" >
<tr>
	Количество пропусков:<br>
	<?php foreach ($omissions as $count):?>
		<strong><?=$count['count(rating)'];?></strong>
	<?php endforeach;?>

</tr>




</table>
</td>
</table>