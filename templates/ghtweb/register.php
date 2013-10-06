<div class="page-title">
	<h1>Регистрация Мастер аккаунта</h1>
</div>

<?php echo $message ?>


<?php if(config('register_allow') == 1) { ?>
	<form action="" method="POST" class="form">
	    
		<div class="row">
			<label for="login">Логин</label>
			<div class="controls">
				<input type="text" name="login" id="login" value="<?php echo set_value('login') ?>" />
			</div>
		</div>
		<div class="row">
			<label for="email">Email</label>
			<div class="controls">
				<input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
			</div>
		</div>
		<div class="row">
			<label for="password">Пароль</label>
			<div class="controls">
				<input type="password" name="password" id="password" />
			</div>
		</div>
		<div class="row">
			<label for="repassword">Повтор пароля</label>
			<div class="controls">
				<input type="password" name="repassword" id="repassword" />
			</div>
		</div>
		<?php if($recaptcha !== FALSE) { ?>
			<div class="row">
				<label></label>
				<div class="controls">
					<div class="captcha"><?php echo $recaptcha ?></div>
				</div>
			</div>
		<?php } ?>
		<div class="row">
			<label></label>
			<div class="controls">
				<button type="submit" name="submit">Зарегистрироваться</button>
			</div>
		</div>
		
	</form>
<?php } else { ?>
	Регистрация отключена
<?php } ?>