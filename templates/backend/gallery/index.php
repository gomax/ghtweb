<div class="page-header">
    <h1>Галерея <small>список</small> <span class="badge badge-info"><?php echo count($gallery_content) ?></span></h1>
</div>

<?php if($this->session->flashdata('message')) { ?>
    <?php echo $this->session->flashdata('message') ?>
<?php } ?>

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
                    <img src="/<?php echo $this->config->item('gallery_path') ?>/<?php echo get_thumb($image['img']) ?>" alt="" class="img-polaroid">
                </a>
            </li>
        <?php } ?>
    <?php } else { ?>
        Раздел пуст
    <?php } ?>
</div>