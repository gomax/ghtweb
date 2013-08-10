<?php if($content) { ?>
	<?php foreach($content as $row) { ?>
		<article class="block">
			<div class="title"><a href="/news/detail/<?php echo $row['id'] ?>/"><?php echo e($row['title']) ?></a></div>
			<div class="info">
				<span class="date"><?php echo date('d.m.Y', strtotime($row['created_at'])) ?></span>,
				<span class="author"><?php echo $row['author'] ?></span>
			</div>
			<div class="text"><?php echo $row['description'] ?></div>
		</article>
	<?php } ?>
    <?php echo $pagination ?>
<?php } else { ?>
    Новостей нет
<?php } ?>