<div class="page-header">
    <h1>Шаблоны для сайта <small>список</small></h1>
</div>

<?php if($content) { ?>
	
	<menu class="themes">
		<?php foreach($content as $theme) { ?>
			<li>
				<figure>
					<img src="<?php echo $theme->theme_img ?>" alt="" />
				</figure>
				<h3><?php echo $theme->name ?></h3>
				<p class="theme-author">Автор: <?php echo $theme->author ?></p>
				<a href="/backend/themes/install/<?php echo $theme->id ?>/">установить</a>
			</li>
		<?php } ?>
	</menu>

<?php } else { ?>
	Шаблоны не найдены
<?php } ?>