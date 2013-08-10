<div class="page-header">
    <h1>Настройки <small>добавление</small></h1>
</div>

<?php echo $message ?>

<?php echo form_open('', 'class="form-horizontal"') ?>
    <fieldset>
        <div class="control-group<?php echo (form_error('key') ? ' error' : '') ?>">
            <label for="key" class="control-label">Ключ <span class="red">*</span></label>
            <div class="controls">
                <input type="text" name="key" id="key" value="<?php echo set_value('key') ?>" class="span10" placeholder="Введите ключ, к примеру my_key" />
                <?php if(form_error('key')) { ?>
                <p class="help-block"><?php echo form_error('key') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('value') ? ' error' : '') ?>">
            <label for="value" class="control-label">Значение <span class="red">*</span></label>
            <div class="controls">
                <input type="text" name="value" id="value" value="<?php echo set_value('value') ?>" class="span10" placeholder="Введите значение" />
                <?php if(form_error('value')) { ?>
                <p class="help-block"><?php echo form_error('value') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('name') ? ' error' : '') ?>">
            <label for="name" class="control-label">Название <span class="red">*</span></label>
            <div class="controls">
                <input type="text" name="name" id="name" value="<?php echo set_value('name') ?>" class="span10" placeholder="Введите название" />
                <?php if(form_error('name')) { ?>
                <p class="help-block"><?php echo form_error('name') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('description') ? ' error' : '') ?>">
            <label for="description" class="control-label">Описание</label>
            <div class="controls">
                <textarea name="description" cols="30" rows="10" id="description" placeholder="Введите Описание" style="width: 770px;"><?php echo set_value('description') ?></textarea>
                <?php if(form_error('description')) { ?>
                <p class="help-block"><?php echo form_error('description') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('group_id') ? ' error' : '') ?>">
            <label class="control-label">Раздел <span class="red">*</span></label>
            <div class="controls">
                <?php echo form_dropdown('group_id', $settings_group, set_value('group_id')) ?>
                <?php if(form_error('group_id')) { ?>
                <p class="help-block"><?php echo form_error('group_id') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('type') ? ' error' : '') ?>">
            <label class="control-label">Тип поля <span class="red">*</span></label>
            <div class="controls">
                <?php echo form_dropdown('type', array_combine($field_types, $field_types), set_value('type')) ?>
                <?php if(form_error('type')) { ?>
                <p class="help-block"><?php echo form_error('type') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="control-group<?php echo (form_error('param') ? ' error' : '') ?> params dn">
            <label for="param" class="control-label">Параметры</label>
            <div class="controls">
                <input type="text" name="param" id="param" value="<?php echo set_value('param') ?>" class="span10" placeholder="Введите параметры через запятую" />
                <p class="help-block">Параметры для выпадающего списка (через запятую)</p>
                <?php if(form_error('param')) { ?>
                <p class="help-block"><?php echo form_error('param') ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit" name="submit">Добавить</button>
            <a href="/backend/" class="btn">Отмена</a>
        </div>
    </fieldset>
<?php echo form_close() ?>

<script type="text/javascript">
    $(function(){
        var types = function()
        {
            if($('select[name=type]').val() == 'dropdown')
            {
                $('.params').removeClass('dn');
            }
            else
            {
                $('.params').addClass('dn');
            }
        }
        types();
        $('select[name=type]').change(types);
    })
</script>