<?php if (isset($list) && count($list) > 0) { ?>

<?php if ($type == 1) { ?>
<table class="record">
    <tbody>
    <tr>
        <th>作業日</th>
        <th>時間</th>
        <th>場所・秤</th>
        <th>品番</th>
        <th>ロット</th>
        <th>員数</th>
        <th>荷姿数量</th>
        <th>数量</th>
        <th>作業者</th>
    </tr>
    <?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
    <tr>
        <td><?= $list[$i]['start_date'] ?></td>
        <td><?= $list[$i]['start_time'] ?></td>
        <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
        <?php if ($list[$i]['pdf_exists']) { ?>
            <td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
        <?php } else { ?>
            <td><?= $list[$i]['number'] ?></td>
        <?php } ?>
        <td><?= $list[$i]['lot'] ?></td>
        <td><?= VarNum($list[$i]['num']) ?></td>
        <td><?= VarNum($list[$i]['packing']) ?></td>
        <td><?= VarNum($list[$i]['total_num']) ?></td>
        <td>
        <?= $list[$i]['worker1_name'] ?>
        <?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>

<?php } elseif ($type == 2) { ?>
<table class="record">
    <tbody>
    <tr>
        <th>作業日</th>
        <th>時間</th>
        <th>場所・秤</th>
        <th>品番</th>
        <th>ロット</th>
        <th>現場エフ数量</th>
        <th>実荷姿数量</th>
        <th>継続フラグ</th>
        <th>作業者</th>
    </tr>
    <?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
    <tr>
        <td><?= $list[$i]['start_date'] ?></td>
        <td><?= $list[$i]['start_time'] ?></td>
        <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
        <?php if ($list[$i]['pdf_exists']) { ?>
            <td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
        <?php } else { ?>
            <td><?= $list[$i]['number'] ?></td>
        <?php } ?>
        <td><?= $list[$i]['lot'] ?></td>
        <td><?= VarNum($list[$i]['num']) ?></td>
        <td><?= VarNum($list[$i]['packing']) ?></td>
        <td><?= $list[$i]['continue_flg_name'] ?></td>
        <td>
        <?= $list[$i]['worker1_name'] ?>
        <?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>

<?php } elseif ($type == 3) { ?>
<table class="record">
    <tbody>
    <tr>
        <th>作業日</th>
        <th>時間</th>
        <th>場所・秤</th>
        <th>品番</th>
        <th>ロット</th>
        <th>数量</th>
        <th>継続フラグ</th>
        <th>作業者</th>
    </tr>
    <?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
    <tr>
        <td><?= $list[$i]['start_date'] ?></td>
        <td><?= $list[$i]['start_time'] ?></td>
        <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
        <?php if ($list[$i]['pdf_exists']) { ?>
            <td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
        <?php } else { ?>
            <td><?= $list[$i]['number'] ?></td>
        <?php } ?>
        <td><?= $list[$i]['lot'] ?></td>
        <td><?= VarNum($list[$i]['num']) ?></td>
        <td><?= $list[$i]['continue_flg_name'] ?></td>
        <td>
        <?= $list[$i]['worker1_name'] ?>
        <?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<?php } ?>

<?php } else { ?>
    <div>no list</div>
<?php } ?>
