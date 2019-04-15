<?php for ( $i = 0, $n = count ($list); $i < $n; $i ++ ) : ?>
  <li id="topics_<?= $list[$i]['id'] ?>" class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <?= $list[$i]['place_name'] ?>
    <input type="button" value="編集" onClick="location.href='http://google.co.jp'">
    <input type="button" value="削除" class="topics_del_btn" data-id="<?= $list[$i]['id'] ?>">
  </li>
<?php endfor ; ?>