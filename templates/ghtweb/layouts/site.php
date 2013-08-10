<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	
	<title><?php echo $meta_title ?></title>
	
	<meta name="keywords" content="<?php echo $meta_keywords ?>" />
	<meta name="description" content="<?php echo $meta_description ?>" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo TPL ?>css/style.css" media="all" />
	
</head>
<body>
	
	<div id="wrapper">
		
		<div class="wrapper-top">
			
			<header class="header">
				<figure class="logo">
					<img src="<?php echo TPL ?>images/logo.png" alt="GHTWEB" />
				</figure>
				<nav class="nav-top">
					<menu>
						<li <?php echo ($this->uri->segment(1) ? '' : 'class="active"') ?>><a href="/">Главная</a></li>
						<li <?php echo ($this->uri->segment(2) == 'files' ? 'class="active"' : '') ?>><a href="/page/files/">Файлы</a></li>
						<li <?php echo ($this->uri->segment(2) == 'about' ? 'class="active"' : '') ?>><a href="/page/about/">О нас</a></li>
						<li <?php echo ($this->uri->segment(2) == 'bonuses' ? 'class="active"' : '') ?>><a href="/page/bonuses/">Бонусы</a></li>
					</menu>
				</nav>
				
				<!-- СТАТУС СЕРВЕРА -->
				<?php echo Server_status::get() ?>
				
			</header>
			
			<aside class="sidebar-left">
				
				<h3>Навигация</h3>
				<nav>
					<menu>
						<li><a href="/news/">Новости</a></li>
						<li><a href="/register/">Регистрация</a></li>
						<li><a href="/login/">Личный кабинет</a></li>
						<li><a href="/stats/">Статистика</a></li>
						<li><a href="/deposit/">Пожертвования</a></li>
						<li><a href="/gallery/">Галерея</a></li>
					</menu>
				</nav>
				
				<?php if($this->auth->is_logged()) { ?>
					<?php $this->load->view('cabinet/navigation') ?>
				<?php } ?>
				
				<!-- ТЕМЫ С ФОРУМА -->
				<?php echo Forum_threads::get() ?>
				
				<h3>ТОП</h3>
				<nav class="tops">
					<menu class="tabs-nav">
						<li class="active"><a>Топ ПВП</a></li>
						<li><a>Топ ПК</a></li>
					</menu>
					<div class="tabs-content">
						<?php echo Top_pvp::get() ?>
					</div>
					<div class="tabs-content dn">
						<?php echo Top_pk::get() ?>
					</div>
				</nav>
				
			</aside>
			
			<section class="content"><?php echo $content ?></section>
			
		</div>
		
		<div class="empty-block"></div>
		
		<div class="wrapper-bottom">
			<footer class="footer">
				&copy; <a href="http://ghtweb.ru" target="_blank">ghtweb</a>
			</footer>
		</div>

	</div>
	
	
	<?php if(us(1) == 'gallery') { ?>
		<!-- FANCYBOX -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.fancybox.css" media="all" />
		<script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.mousewheel-3.0.6.pack.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
		
		<link rel="stylesheet" href="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css" />
		<script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js"></script>
		
		<link rel="stylesheet" href="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css" />
		<script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js"></script>
	<?php } ?>
	
	<!-- CAPTCHA -->
	<script type="text/javascript" src="/resources/libs/captcha_refresh.js"></script>
	
	<script type="text/javascript" src="<?php echo TPL ?>js/main.js"></script>
	
</body>
</html>