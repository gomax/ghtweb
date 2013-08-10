<?php if($content) { ?>
	<div class="page-title">
		<h1><?php echo e($content['title']) ?></h1>
	</div>
	<?php echo $content['text'] ?>
<?php } ?>