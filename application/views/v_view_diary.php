<div class="container">
<h1>Мои предметы</h1>
   

 <table class="table table-bordered" >

    <?php foreach ($get_diary as $object):?>
    	 <tr>
    	<td> <a class="lead" href="my_marks?id=<?=$object['id_journal'];?>&obj=<?=$object['id_object'];?>"><?=$object['name'];?></a><br></td>
    	</tr>
    <?php endforeach;?>





  </table>
  
</div>