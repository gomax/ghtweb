<div class="page-header">
    <h1>Страницы <small>добавление</small></h1>
</div>

<?php echo form_open('', 'class="form-horizontal"') ?>
    <fieldset>
        <div class="control-group">
            <label for="page" class="control-label">Ссылка на страницу</label>
            <div class="controls">
                <input type="text" name="page" id="page" value="<?php echo set_value('page') ?>" class="input-block-level" placeholder="Введите Ссылку латинскими буквами" />
                <p class="help-block">Пример: my-super_page10 (разрешены символы: <b>a-z0-9-_</b>)</p>
            </div>
        </div>
        <div class="control-group">
            <label for="title" class="control-label">Название</label>
            <div class="controls">
                <input type="text" name="title" id="title" value="<?php echo set_value('title') ?>" class="input-block-level" placeholder="Введите Название" />
            </div>
        </div>
        <div class="control-group">
            <label for="text" class="control-label">Текст</label>
            <div class="controls">
                <textarea name="text" id="text" style="height: 400px;" class="input-block-level"><?php echo set_value('text') ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_title" class="control-label">СЕО титул</label>
            <div class="controls">
                <input type="text" name="seo_title" id="seo_title" value="<?php echo set_value('seo_title') ?>" class="input-block-level" placeholder="Введите СЕО титул" />
                <p class="help-block">Используется в &lt;title&gt;</p>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_keywords" class="control-label">СЕО ключевые слова</label>
            <div class="controls">
                <input type="text" name="seo_keywords" id="seo_keywords" value="<?php echo set_value('seo_keywords') ?>" class="input-block-level" placeholder="Введите СЕО ключевые слова" />
                <p class="help-block">Используется в &lt;keywords&gt;</p>
            </div>
        </div>
        <div class="control-group">
            <label for="seo_description" class="control-label">СЕО описание</label>
            <div class="controls">
                <input type="text" name="seo_description" id="seo_description" value="<?php echo set_value('seo_description') ?>" class="input-block-level" placeholder="Введите СЕО описание" />
                <p class="help-block">Используется в &lt;description&gt;</p>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('allow') ? ' error' : '') ?>">
            <label for="allow" class="control-label">Статус</label>
            <div class="controls">
                <?php echo form_dropdown('allow', array('Выкл', 'Вкл'), set_value('allow', 1), 'class="chzn-select"') ?>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit" name="submit">Добавить</button>
            <a href="/backend/pages/" class="btn">Отмена</a>
        </div>
    </fieldset>
<?php echo form_close() ?>


<?php echo tinymce(array('text')) ?>