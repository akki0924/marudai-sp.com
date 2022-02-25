<?php if (isset($list) && count($list) > 0) { ?>
<table class="record">
    <tbody>
    <tr>
        <th>作業日</th>
        <th>時間</th>
        <th>場所・秤</th>
        <th>品番</th>
        <th>ロット</th>
        <th><?= ($type == 1 ? '員数' : '現場エフ数量') ?></th>
        <th><?= ($type == 1 ? '荷姿数量' : '実荷姿数量') ?></th>
        <?= ($type == 1 ? '<th>数量</th>' : '') ?>
        <th>作業者</th>
    </tr>
    <?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
    <tr>
        <td><?= $list[$i]['start_date'] ?></td>
        <td><?= $list[$i]['start_time'] ?></td>
        <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
        <td><?= $list[$i]['number'] ?></td>
        <td><?= $list[$i]['lot'] ?></td>
        <td><?= $list[$i]['num'] ?></td>
        <td><?= $list[$i]['packing'] ?></td>
        <?= ($type == 1 ? '<td>' . $list[$i]['total_num'] . '</td>' : '') ?>
        <td>
        <?= $list[$i]['worker1_name_l'] ?>
        <?= ($list[$i]['worker2_name_l'] ? '・' . $list[$i]['worker2_name_l'] : '') ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<?php } else { ?>
    <div>no list</div>
<?php } ?>
