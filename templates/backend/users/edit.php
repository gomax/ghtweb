<div class="page-header">
    <h1>Пользователи сайта <small>редактирование</small> <span class="badge badge-info"><?php echo $content['login'] ?></span></h1>
</div>

<?php if($content) { ?>
    
    <h3>Информация</h3> <br />
    
    <table class="table table-striped table-bordered">
        <tr>
            <td width="40%">Бан</td>
            <td width="60%"><?php echo ($content['banned'] ? '<span class="label label-important">Да</span> <i class="icon-info-sign" rel="popover" data-content="' . ($content['banned_reason'] != '' ? $content['banned_reason'] : 'Не указана') . '" data-original-title="Причина бана"></i>' : '<span class="label label-success">Нет</span>') ?></td>
        </tr>
        <tr>
            <td>Группа</td>
            <td><?php echo $user_groups[$content['group']] ?></td>
        </tr>
        <tr>
            <td>Привязка аккаунта к IP</td>
            <td><?php echo ($content['protected_ip'] == '' ? '<span class="label label-important">Нет</span>' : '<span class="label label-success">Да</span> [' . $content['protected_ip'] . ']') ?></td>
        </tr>
        <tr>
        	<td>Дата регистрации</td>
        	<td><?php echo $content['created_at'] ?></td>
        </tr>
        <tr>
        	<td>Дата последней авторизации</td>
        	<td><?php echo ($content['last_login'] == '' ? 'Ещё не заходил' : $content['last_login']) ?></td>
        </tr>
        <tr>
        	<td>IP с которого последний раз заходили на аккаунт</td>
        	<td><?php echo ($content['last_ip'] == '' ? 'Ещё не заходил' : $content['last_ip']) ?></td>
        </tr>
    </table>
    
    <h3>Управление</h3><br />
    
    <?php echo $message ?>
    
    <?php echo form_open('', 'class="form-horizontal"') ?>
        <fieldset>
            <input type="hidden" name="old_password" value="<?php echo $content['password'] ?>" />
            <div class="control-group<?php echo (form_error('password') ? ' error' : '') ?>">
                <label for="password" class="control-label">Новый пароль</label>
                <div class="controls">
                    <input type="text" name="password" id="password" value="<?php echo ($message ? '' : set_value('password')) ?>" class="span10" placeholder="Введите новый пароль" />
                    <p class="help-block">Если хотите сменить пароль то введите его выше</p>
                    <?php if(form_error('password')) { ?>
                        <p class="help-block"><?php echo form_error('password') ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="control-group<?php echo (form_error('email') ? ' error' : '') ?>">
                <label for="email" class="control-label">Email</label>
                <div class="controls">
                    <input type="text" name="email" id="email" value="<?php echo set_value('email', $content['email']) ?>" class="span10" placeholder="Введите Email" />
                    <?php if(form_error('email')) { ?>
                        <p class="help-block"><?php echo form_error('email') ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="control-group<?php echo (form_error('protected_ip') ? ' error' : '') ?>">
                <label for="protected_ip" class="control-label">Защита аккаунта по IP</label>
                <div class="controls">
                    <input type="text" name="protected_ip" id="protected_ip" value="<?php echo set_value('protected_ip', $content['protected_ip']) ?>" class="span10" placeholder="Введите IP адрес к которому надо привязать аккаунт, если их несколько то через запятую" />
                    <?php if(form_error('protected_ip')) { ?>
                        <p class="help-block"><?php echo form_error('protected_ip') ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="control-group<?php echo (form_error('group') ? ' error' : '') ?>">
                <label class="control-label">Группа</label>
                <div class="controls">
                    <?php echo form_dropdown('group', $user_groups, set_value('group', $content['group'])) ?>
                    <?php if(form_error('group')) { ?>
                        <p class="help-block"><?php echo form_error('group') ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" name="submit">Сохранить</button>
                <a href="/backend/users/" class="btn">Отмена</a>
            </div>
        </fieldset>
    <?php echo form_close() ?>

<?php } else { ?>
    <?php echo Message::info('Пользователь не найден') ?>
<?php } ?>