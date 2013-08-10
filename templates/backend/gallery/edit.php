<div class="page-header">
    <h1>Галерея <small>редактирование картинки</small></h1>
</div>

<?php echo form_open_multipart('', 'class="form-horizontal"') ?>
    <input type="hidden" name="old_img" value="<?php echo $content['img'] ?>" />
    <fieldset>
        <div class="control-group<?php echo (form_error('img') ? ' error' : '') ?>">
            <label for="img" class="control-label">Выберите картинку</label>
            <div class="controls">
                <input type="file" name="img" /><br /><br />
                
                <?php if(file_exists(FCPATH . $this->config->item('gallery_path') . '/' . $content['img'])) { ?>
                    <a href="/<?php echo $this->config->item('gallery_path') ?>/<?php echo $content['img'] ?>" class="fancybox">
                        <img src="/<?php echo $this->config->item('gallery_path') ?>/<?php echo get_thumb($content['img']) ?>" alt="" />
                    </a>
                <?php } ?>
                
                <?php if(form_error('img')) { ?>
                    <p class="help-block"><?php echo form_error('img') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('allow') ? ' error' : '') ?>">
            <label for="allow" class="control-label">Статус</label>
            <div class="controls">
                <input type="hidden" name="allow" value="<?php echo set_value('allow', 1) ?>" />
                <div data-toggle="buttons-radio" class="btn-group">
                    <button class="btn btn-success <?php echo set_value('allow', $content['allow']) == 1 ? 'active' : '' ?>" type="button" data-value="1">Вкл</button>
                    <button class="btn btn-danger <?php echo set_value('allow', $content['allow']) == 0 ? 'active' : '' ?>" type="button" data-value="0">Выкл</button>
                </div>
                <?php if(form_error('allow')) { ?>
                    <p class="help-block"><?php echo form_error('allow') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit" name="submit">Сохранить</button>
            <a href="/backend/news/" class="btn">Отмена</a>
        </div>
    </fieldset>
<?php echo form_close() ?>