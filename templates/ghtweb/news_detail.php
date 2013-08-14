<?php if($content) { ?>
	<div class="page-title">
		<h1><?php echo e($content['title']) ?></h1>
	</div>
	<?php echo $content['text'] ?>
    
    <?php if($this->config->item('news_socials')) { ?>
        <link rel="stylesheet" href="<?php echo base_url() ?>resources/libs/share42/10.02.2013/share42.css">
        <script src="<?php echo base_url() ?>resources/libs/share42/10.02.2013/share42.js"></script>
        <div class="share42init" data-title="<?php echo html_escape($content['title']) ?>"></div>
    <?php } ?>
<?php } ?>