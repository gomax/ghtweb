<div class="page-header">
    <h1>Персонажи <small>предметы на персонаже</small>
        <span class="badge badge-info"><?php echo $char_data['char_name'] ?></span>
        <small>сервер</small>
        <span class="badge badge-info"><?php echo $server_list['servers'][$server_id]['name'] ?></span>
    </h1>
</div>

<table class="table table-striped table-bordered">
    <tr>
        <td width="20%"><b>Аккаунт:</b> <?php echo $char_data['account_name'] ?> <a href="/backend/characters/<?php echo $server_id ?>/<?php echo $char_data['account_name'] ?>/" rel="tooltip" title="Все персонажи на этом аккаунте">ещё</a></td>
        <td width="20%"><b>Имя:</b> <?php echo $char_data['char_name'] ?></td>
        <td width="20%"><b>Уровень:</b> <?php echo $char_data['level'] ?></td>
        <td width="20%"><b>ХП:</b> <?php echo reset(explode('.', $char_data['curHp'])) ?></td>
        <td width="20%"><b>МП:</b> <?php echo reset(explode('.', $char_data['curMp'])) ?></td>
    </tr>
    <tr>
    	<td><b>Пол:</b> <img src="/resources/images/sex/<?php echo $char_data['sex'] ?>.png" alt="" rel="tooltip" title="<?php echo ($char_data['sex'] ? 'female' : 'male') ?>" /></td>
    	<td><b>Карма:</b> <?php echo $char_data['karma'] ?></td>
    	<td><b>Пвп:</b> <?php echo $char_data['pvpkills'] ?></td>
    	<td><b>Пк:</b> <?php echo $char_data['pkkills'] ?></td>
    	<td><b>Клас:</b> <?php echo get_class_name_by_id($char_data['base_class']) ?></td>
    </tr>
    <tr>
    	<td><b>Титул:</b> <?php echo ($char_data['title'] == '' ? 'нет' : $char_data['title']) ?></td>
    	<td><b>Online:</b> <?php echo ($char_data['online'] ? '<span class="green">online</span>' : '<span class="red">offline</span>') ?></td>
    	<td><b>Время в игре:</b> <?php echo online_time($char_data['onlinetime']) ?></td>
    	<td><b>Клан:</b> <?php echo ($char_data['clan_name'] == '' ? 'n/a' : $char_data['clan_name']) ?></td>
    	<td></td>
    </tr>
</table>

<?php if($this->session->flashdata('message')) { ?>
    <?php echo $this->session->flashdata('message') ?>
<?php } ?>

<?php echo $message ?>

<table class="table table-striped table-bordered">
    <tr>
        <th width="5%"></th>
        <th width="61%">Название</th>
        <th width="10%">Кол-во</th>
        <th width="10%">Находится</th>
        <th width="10%">Заточка</th>
        <th width="4%"></th>
    </tr>
    <?php if($content) { ?>
        <?php foreach($content as $row) { ?>
            <tr>
                <td><img src="<?php echo (file_exists(FCPATH . 'resources/images/items/' . $row['item_id'] . '.gif') ? '/resources/images/items/' . $row['item_id'] . '.gif' : '/resources/images/items/blank.gif') ?>" alt="" title="<?php echo (!isset($row['item_name']) ? 'Название не найдено' : $row['item_name']) ?>" /></td>
            	<td><?php echo (!isset($row['item_name']) ? 'Название не найдено' : $row['item_name']) ?> (<?php echo $row['item_id'] ?>)</td>
                <td><?php echo number_format($row['count'], 0, '', '.') ?></td>
            	<td><?php echo $row['loc'] ?></td>
            	<td><?php echo ($row['enchant_level'] > 0 ? '<font color="' . definition_enchant_color($row['enchant_level']) . '">' . $row['enchant_level'] . '</font>' : $row['enchant_level']) ?></td>
                <td>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
                            <ul class="dropdown-menu" style="z-index: 999;">
                                <li><a href="/backend/character/delete-item/<?php echo $server_id ?>/<?php echo $row['object_id'] ?>/"><i class="icon-trash"></i> Удалить</a></li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
        	<td colspan="6">Предметы не найдены</td>
        </tr>
    <?php } ?>

</table>

<?php echo $pagination ?>