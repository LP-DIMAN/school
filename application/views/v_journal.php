<?php echo validation_errors(); ?>

<?php echo form_open('journal'); ?>
<div class="container">
<h1>Доступные журналы</h1>
<?php if(isset($view_access)):?>
<?php foreach ($view_access as $access):?>

	<a href="view_journal?id=<?=$access['id_journal'];?>"><h1><?=$access['title'];?> | <?=$access['id_class'];?> | <?=$access['date_start'];?> | <?=$access['date_end'];?></h1></a>
	<?php break;?>
<?php endforeach;?>
<?php endif;?>
<hr>
</div>
<div class="container">
<h2>Созданные журналы</h2>
<?php if (isset($get_journal)):?>
<?php foreach ($get_journal as $get): ?>
	

<a href="view_journal?id=<?=$get['id_journal'];?>"><h1><?=$get['title'];?> | <?=$get['id_class'];?> | <?=$get['date_start'];?> | <?=$get['date_end'];?></h1></a>

<a href="verification?id=<?=$get['id_journal'];?>" class="btn btn-danger">Удалить</a>


	<?php endforeach;?>
<?php endif;?>
	</div>