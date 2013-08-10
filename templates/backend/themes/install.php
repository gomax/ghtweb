<div class="page-header">
    <h1>Шаблоны для сайта <small>установка</small></h1>
</div>

<?php echo $message ?>

<?php if($content) { ?>
	
	<p>Идёт установка, ждите...</p>
	
	<?php prt($content) ?>
	
<?php } ?>