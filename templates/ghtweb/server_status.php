<div class="server-status">
	<?php if(is_array($server_status)) { ?>
			<table>
				<thead>
					<tr>
						<th>Название</th>
						<th>Сервер</th>
						<th>Логин</th>
						<th>В игре</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($server_status as $server_id => $row) { ?>
						<tr>
							<td><span class="s-name"><?php echo $row['name'] ?></span></td>
							<td align="center"><span class="<?php echo $row['server_status'] ?>"><?php echo $row['server_status'] ?></span></td>
							<td align="center"><span class="<?php echo $row['login_status'] ?>"><?php echo $row['login_status'] ?></span></td>
							<td align="center"><span class="s-count-online"><?php echo $row['online'] ?></span></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<?php if(count($server_status) > 1) { ?>
						<tr>
							<td colspan="3">Общий онлайн</td>
							<td align="center"><?php echo $total_online ?></td>
						</tr>
					<?php } ?>
				</tfoot>
			</table>
	<?php } else { ?>
		<?php echo $server_status ?>
	<?php } ?>
</div>