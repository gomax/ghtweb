<table>
    <?php if(is_array($top_pvp) && count($top_pvp)) { ?>
        <?php foreach($top_pvp as $row) { ?>
            <tr>
            	<td><?php echo $row['char_name'] ?> <p><?php echo get_class_name_by_id($row['base_class']) ?> [<?php echo $row['level'] ?>]</p></td>
            	<td><?php echo $row['pvpkills'] ?></td>
            </tr>
        <?php } ?>
	<?php } elseif(is_array($top_pvp)) { ?>
		<tr>
			<td colspan="2">Нет данных</td>
		</tr>
    <?php } else { ?>
        <tr>
			<td colspan="2"><?php echo $top_pvp ?></td>
		</tr>
    <?php } ?>
</table>