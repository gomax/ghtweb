<p class="deposit-title">Выберите сервер</p>
<menu>
	<?php foreach($servers as $server_id => $server_name) { ?>
		<li><a href="/deposit/<?php echo $server_id ?>/"><?php echo $server_name ?></a></li>
	<?php } ?>
</menu>