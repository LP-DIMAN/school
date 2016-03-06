<?php echo validation_errors(); ?>

<?php echo form_open('diary'); ?>
<div class="container">

<?php foreach ($get_diary as $get): ?>
	

<a href="view_diary?id=<?=$get['id_journal'];?>"><h1> <?=$get['id_class'];?> | <?=$get['date_start'];?> | <?=$get['date_end'];?></h1></a>




	<?php endforeach;?>
	</div>