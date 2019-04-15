<?php for ( $i = 0, $n = count ($list); $i < $n; $i ++ ) : ?>
<table class="table" id="place_<?= $list[$i]['id'] ?>">
  <tbody>
    <tr>
      <th width="4%" class="bg_c003">
        <button type="button" class="btn btn-block btn-info"><i class="fa fa-sort"></i> 移動</button>
      </th>
      <td width="86%"><p class="h2"><?= $list[$i]['name'] ?></p></td>
      <td width="4%">
        <button type="button" class="btn btn-block btn-default place_edit_btn" data-id="<?= $list[$i]['id'] ?>">
            <i class="fa fa-edit"></i> 編集
        </button>
      </td>
      <td width="4%">
        <button type="button" class="btn btn-block btn-default place_del_btn" data-id="<?= $list[$i]['id'] ?>">
            <i class="fa fa-times-circle"></i> 削除
        </button>
      </td>
    </tr>
 </tbody>
</table>
<?php endfor ; ?>