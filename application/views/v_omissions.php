<div class="table-responsive">
  <table class="table">
    
<th class="warning">Пропуски по всем предметам </th>
<?php foreach ($get_pupils as $pupil):?>
	<tr>
<td>
	<?echo $pupil['surname']."&nbsp;"; echo $pupil['name']."&nbsp;"; echo $pupil['patronymic'];?>
</td>
</tr>
<?php endforeach ;?>

  </table>
</div>