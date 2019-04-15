<?php for ( $i = 0, $n = count ($list); $i < $n; $i ++ ) : ?>
<table class="table" id="topics_<?= $list[$i]['id'] ?>">
    <tbody>
        <tr>
            <td width="70%"><p><?= $list[$i]['title'] ?></p></td>
            <td width="4%">
                <button type="button" class="btn btn-block btn-default topics_edit_btn" data-id="<?= $list[$i]['id'] ?>">
                    <i class="fa fa-edit"></i> 編集
                </button>
            </td>
            <td width="4%">
                <button type="button" class="btn btn-block btn-default topics_del_btn" data-id="<?= $list[$i]['id'] ?>">
                    <i class="fa fa-times-circle"></i> 削除
                </button>
            </td>
            <td width="22%">
                <?php foreach ( $status_list AS $status_key => $status_val ) : ?>
                <?= form_radio( "status" . $list[$i]['id'], $status_key, ( isset ( $list[$i]['status'] ) && $list[$i]['status'] == $status_key ? true : false ), "id=status" . $list[$i]['id'] . "_" . $status_key . " class='schedule_status' data-id='" . $list[$i]['id'] . "'" ); ?>
                <?= form_label( $status_val, "status" . $list[$i]['id'] . "_" . $status_key, "class='mr-4'" ); ?>
                <?php endforeach; ?>
            </td>
        </tr>
    </tbody>
</table>
<?php endfor ; ?>