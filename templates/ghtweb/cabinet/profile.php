<div class="page-title">
	<h1>Редактирование профиля</h1>
</div>

<?php echo $message ?>

<?php if($this->session->flashdata('message')) { ?>
    <?php echo $this->session->flashdata('message') ?>
<?php } ?>

<form action="" method="POST">
    
    <div class="row">
        <label>Логин</label>
        <div class="controls">
            <input type="text" name="" disabled="" value="<?php echo $this->auth->get('login') ?>" />
        </div>
    </div>
    <div class="row">
        <label for="new_password">Новый пароль</label>
        <div class="controls">
            <input type="password" name="new_password" id="new_password" />
        </div>
    </div>
    <div class="row">
        <label for="renew_password">Повтор нового пароля</label>
        <div class="controls">
            <input type="password" name="renew_password" id="renew_password" />
        </div>
    </div>
    <div class="row">
        <label for="email">Email</label>
        <div class="controls">
            <input type="text" name="email" id="email" value="<?php echo set_value('email', $this->auth->get('email')) ?>" />
        </div>
    </div>
    <div class="row">
        <label for="protected_ip">IP адреса (если больше одного то через запятую)</label>
        <div class="controls">
            <textarea name="protected_ip" id="protected_ip" cols="20" rows="4"><?php echo $this->auth->get('protected_ip') ?></textarea>
        </div>
    </div>
    <div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Сохранить</button>
		</div>
	</div>

</form>