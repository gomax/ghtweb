<div class="page-title">
	<h1>Статистика</h1>
</div>

<!-- список серверов -->
<?php if($server_list) { ?>
    <nav class="stats-list">
        <menu>
            <?php foreach($server_list as $server) { ?>
                <li <?php echo ($server_id == $server['id'] ? 'class="active"' : '') ?>><a href="/stats/<?php echo $server['id'] ?>/"><?php echo $server['name'] ?></a></li>
            <?php } ?>
        </menu>
    </nav>
<?php } ?>

<!-- типы статистики -->
<?php if($stats_list) { ?>
    <nav class="stats-list">
        <menu>
            <?php foreach($stats_list as $type) { ?>
                <li <?php echo ($current_type == $type ? 'class="active"' : '') ?>><a href="/stats/<?php echo ($this->uri->segment(2) ? $this->uri->segment(2) : $server_id) ?>/<?php echo $type ?>/"><?php echo get_stats_name($type) ?></a></li>
            <?php } ?>
        </menu>
    </nav>
<?php } ?>


<?php echo $message ?>

<?php echo $content ?>