<div class="page-title">
	<h1>Регистрация игрового аккаунта</h1>
</div>

<?php echo $message ?>

<form action="" method="POST">
    
    <?php if(count($logins) > 1) { ?>
        <div class="row">
            <label>Выберите сервер</label>
            <div class="controls">
                <?php echo form_dropdown('login_id', $logins_for_select) ?>
            </div>
        </div>
    <?php } ?>
    
    <?php if(is_array($prefixes)) { ?>
        <div class="row">
            <label for="login">Выберите префикс</label>
            <div class="controls">
                <?php echo form_dropdown('prefix', $prefixes, set_value('prefix')) ?>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <label for="login">Логин</label>
        <div class="controls">
            <input type="text" name="login" id="login" />
        </div>
    </div>
    <div class="row">
        <label for="password">Пароль</label>
        <div class="controls">
            <input type="password" name="password" id="password" />
        </div>
    </div>
    <div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Зарегистрировать</button>
		</div>
	</div>

</form>