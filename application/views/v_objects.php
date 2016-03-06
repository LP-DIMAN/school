<div class="table-responsive">
  <table class="table">
    
<th class="warning">Предметы,которые проходят ученики </th>
<?php foreach ($objects_pupils as $object):?>
	<tr>
<td>
	<?=$object['name'];?>
</td>
</tr>
<?php endforeach ;?>

  </table>
</div>