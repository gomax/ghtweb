<table>
    <?php if(is_array($top_pk) && count($top_pk)) { ?>
        <?php foreach($top_pk as $row) { ?>
            <tr>
            	<td><?php echo $row['char_name'] ?> <p><?php echo get_class_name_by_id($row['base_class']) ?> [<?php echo $row['level'] ?>]</p></td>
            	<td><?php echo $row['pkkills'] ?></td>
            </tr>
        <?php } ?>
    <?php } elseif(is_array($top_pk)) { ?>
		<tr>
			<td colspan="2">Нет данных</td>
		</tr>
    <?php } else { ?>
        <tr>
			<td colspan="2"><?php echo $top_pk ?></td>
		</tr>
    <?php } ?>
</table>