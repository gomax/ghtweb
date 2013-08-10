<div class="page-header">
    <h1>Новости <small>редактирование</small></h1>
</div>

<?php echo form_open('', 'class="form-horizontal"') ?>
    <fieldset>
        <div class="control-group">
            <label for="title" class="control-label">Название</label>
            <div class="controls">
                <input type="text" name="title" id="title" value="<?php echo set_value('title', $content['title']) ?>" class="input-block-level" placeholder="Введите Название" />
            </div>
        </div>
        <div class="control-group">
            <label for="description" class="control-label">Описание</label>
            <div class="controls">
                <textarea name="description" id="description" style="height: 300px;" cols="30" class="input-block-level"><?php echo set_value('description', $content['description']) ?></textarea>
                <p class="help-block">Короткое описание новости</p>
            </div>
        </div>
        <div class="control-group">
            <label for="text" class="control-label">Текст</label>
            <div class="controls">
                <textarea name="text" id="text" style="height: 400px;" class="input-block-level"><?php echo set_value('text', $content['text']) ?></textarea>
                <p class="help-block">Полное описание новости</p>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_title" class="control-label">СЕО титул</label>
            <div class="controls">
                <input type="text" name="seo_title" id="seo_title" value="<?php echo set_value('seo_title', $content['seo_title']) ?>" class="span10" placeholder="Введите СЕО титул" />
                <p class="help-block">Используется в &lt;title&gt;</p>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_keywords" class="control-label">СЕО ключевые слова</label>
            <div class="controls">
                <input type="text" name="seo_keywords" id="seo_keywords" value="<?php echo set_value('seo_keywords', $content['seo_keywords']) ?>" class="span10" placeholder="Введите СЕО ключевые слова" />
                <p class="help-block">Используется в &lt;keywords&gt;</p>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_description" class="control-label">СЕО описание</label>
            <div class="controls">
                <input type="text" name="seo_description" id="seo_description" value="<?php echo set_value('seo_description', $content['seo_description']) ?>" class="span10" placeholder="Введите СЕО описание" />
                <p class="help-block">Используется в &lt;description&gt;</p>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('allow') ? ' error' : '') ?>">
            <label for="allow" class="control-label">Статус</label>
            <div class="controls">
                <?php echo form_dropdown('allow', array('Выкл', 'Вкл'), set_value('allow', $content['allow']), 'class="chzn-select"') ?>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit" name="submit">Сохранить</button>
            <a href="/backend/news/" class="btn">Отмена</a>
        </div>
    </fieldset>
<?php echo form_close() ?>


<?php echo tinymce(array('description', 'text')) ?>
