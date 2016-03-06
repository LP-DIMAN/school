<div class="table-responsive">
<h1>Успеваемость по всем предметам</h1>
   

 <table class="table table-bordered" >


    <?php foreach ($get_objects_from_journal as $object):?>
    	 <tr>
    	<td> <a class="lead" href="progress?id=<?=$object['id_journal'];?>&obj=<?=$object['id_object'];?>"><?=$object['name'];?></a><br></td>
    	</tr>
    <?php endforeach;?>





  </table>
  
</div>