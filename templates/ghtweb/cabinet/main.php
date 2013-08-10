<div class="page-title">
	<h1>Добро пожаловать, <?php echo $this->auth->get('login') ?>!</h1>
</div>

<?php echo $message ?>

<menu>
    <li>- Последний раз Вы заходили <i><?php echo $this->auth->get('last_login') ?></i> с IP <i><?php echo $this->auth->get('last_ip') ?></i></li>
    <li>- Дата регистрации Мастер аккаунта <i><?php echo $this->auth->get('created_at') ?></i></li>
    <li>- Игровых аккаунтов <i><?php echo $count_game_accounts ?></i> из <i><?php echo $this->config->item('count_game_accounts_allowed') ?></i></li>
    <li>- Основной Email <i><?php echo $this->auth->get('email') ?></i></li>
    <li>- Привязка аккаунта к IP <?php echo ($this->auth->get('protected_ip') == '' ? '<i>отключена</i>' : '<i>' . $this->auth->get('protected_ip') . '</i>') ?></li>
</menu>

<?php if($count_game_accounts < 1) { ?>
    <br />
    <p>
        У вас нет ни единого игрового аккаунта. <a href="/cabinet/create_game_account/">Хотите создать его?</a>
    </p>
<?php } ?>
