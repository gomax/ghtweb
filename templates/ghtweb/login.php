<div class="page-title">
	<h1>Вход в Мастер аккаунт</h1>
</div>

<?php echo $message ?>

<form action="" method="POST" class="form">
    
	<div class="row">
		<label for="login">Логин</label>
		<div class="controls">
			<input type="text" name="login" id="login" value="<?php echo set_value('login') ?>" />
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
			<button type="submit" name="submit">Войти</button>
			<a href="/forgotten_password/" class="forgot-pswd">Забыли пароль?</a>
		</div>
	</div>

</form>