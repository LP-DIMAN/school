<?php echo validation_errors(); ?>

<?php echo form_open(''); ?>
<div class="container">
<?php foreach ($edit_access as $edit): ?>
	


<h1><?=$edit['title'];?> | <?=$edit['id_class'];?> | <?=$edit['date_start'];?> | <?=$edit['date_end'];?></h1>
<a href="progress_objects?id=<?=$edit['id_journal'];?>"class="btn btn-success">Посмотреть успеваемость и количество пропусков по всем предметам </a>

<a href="objects?id=<?=$edit['id_journal'];?>" class="btn btn-warning">Предметы, которые проходят ученики</a>


	<?php endforeach;?>

	<?php if(isset($get_journal)):?>
		<?php foreach ($get_journal as $get):?>
			<h1><?=$get['title'];?> | <?=$get['id_class'];?> | <?=$get['date_start'];?> | <?=$get['date_end'];?></h1>
<a href="progress_objects?id=<?=$get['id_journal'];?>"class="btn btn-success">Посмотреть успеваемость и количество пропусков по всем предметам</a>

<a href="objects?id=<?=$get['id_journal'];?>" class="btn btn-warning">Предметы, которые проходят ученики</a>
		<?php endforeach;?>
		
<?php endif;?>
</div>