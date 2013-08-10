<h3>Темы с форума</h3>
<nav class="forum-threads">
	<menu>
		<?php if(is_array($forum_threads)) { ?>
			<?php foreach($forum_threads as $row) { ?>
				<li>
					<div class="title">
						<a href="<?php echo $row['theme_link'] ?>" title="<?php echo $row['title'] ?>" target="_blank"><?php echo character_limiter($row['title'], $forum_character_limit) ?></a>
					</div>
					<div class="info">
						<a href="<?php echo $row['user_link'] ?>"><?php echo $row['starter_name'] ?></a>, <span><?php echo date('d.m.Y', strtotime($row['start_date'])) ?></span>
					</div>
				</li>
			<?php } ?>
		<?php } else { ?>
			<li><?php echo $forum_threads ?></li>
		<?php } ?>
	</menu>
</nav>