<div class="page-title">
	<h1>Галерея</h1>
</div>
				
<?php if($content) { ?>
    <div class="gallery">
        <ul>
            <?php foreach($content as $row) { ?>
                <li>
					<a class="fancybox-button" rel="fancybox-button" href="/<?php echo $this->config->item('gallery_path') ?>/<?php echo $row['img'] ?>">
						<img src="/<?php echo $this->config->item('gallery_path') ?>/<?php echo get_thumb($row['img']) ?>" alt="" style="margin-top: <?php echo get_margin_top($row['img'], 171) ?>px;" />
					</a>
				</li>
            <?php } ?>
        </ul>
    </div>
	<div class="clear"></div>
    <?php echo $pagination ?>
<?php } else { ?>
    <div class="alert alert-info">Галерея пуста</div>
<?php } ?>
