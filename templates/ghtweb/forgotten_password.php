<div class="page-title">
	<h1>Восстановление пароля от Мастер аккаунта</h1>
</div>

<?php echo $message ?>

<form action="" method="POST" class="form">
    
    <div class="row">
        <label for="login">Логин</label>
        <div class="controls">
            <input type="text" name="login" id="login" />
        </div>
    </div>
    <div class="row">
        <label></label>
        <div class="controls">
            <div class="captcha"><?php echo $captcha ?></div>
        </div>
    </div>
    <div class="row">
        <label for="captcha">Код с картинки</label>
        <div class="controls">
            <input type="text" name="captcha" id="captcha" />
        </div>
    </div>
    <div class="row">
		<label></label>
		<div class="controls">
			<button type="submit" name="submit">Восстановить</button>
		</div>
	</div>
    
</form>