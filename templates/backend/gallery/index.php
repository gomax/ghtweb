<div class="page-header">
    <h1>Галерея <small>список</small> <span class="badge badge-info"><?php echo count($gallery_content) ?></span></h1>
</div>

<?php if($this->session->flashdata('message')) { ?>
    <?php echo $this->session->flashdata('message') ?>
<?php } ?>

<link rel="stylesheet" type="text/css" href="/resources/libs/fancybox/2.1.3/jquery.fancybox.css" media="all" />
<script type="text/javascript" src="/resources/libs/fancybox/2.1.3/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="/resources/libs/fancybox/2.1.3/jquery.fancybox.pack.js"></script>

<script type="text/javascript">
$(function(){
    $('.fancybox').fancybox();
})
</script>

<div class="gallery">
    <?php if($gallery_content) { ?>
        <?php foreach($gallery_content as $image) { ?>
            <li>
                <div class="control">
                    <?php echo ($image['allow'] ? '<span class="label label-success">вкл</span>' : '<span class="label label-important">выкл</span>') ?>
                    <a href="/backend/gallery/edit/<?php echo $image['id'] ?>/" class="btn btn-mini" title="Редактировать">edit</a>
                    <a href="/backend/gallery/del/<?php echo $image['id'] ?>/" class="btn btn-mini btn-danger del" title="Удалить">del</a>
                </div>
                <a class="fancybox" rel="gallery" href="/<?php echo $this->config->item('gallery_path') ?>/<?php echo $image['img'] ?>">
                    <img src="/<?php echo $this->config->item('gallery_path') ?>/<?php echo get_thumb($image['img']) ?>" alt="" />
                </a>
            </li>
        <?php } ?>
    <?php } else { ?>
        <?php echo Message::info('Раздел пуст') ?>
    <?php } ?>
</div>