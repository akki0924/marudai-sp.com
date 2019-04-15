<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $type_name ?>一覧｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
  
  <link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
  <style>
    #dialog_edit, #dialog_del, #info { display: none; }
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>

  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
  <script>
  var place_ajax_sort_url = "<?= site_dir(); ?>admin/place/sort_part";
  var place_ajax_del_url = "<?= site_dir(); ?>admin/place/del_part";
  </script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/place_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->
<form action="<?=site_dir();?>admin/place/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">

<div class="card-header"><p class="card-title"><i class="fa fa-building"></i> <?= $type_name ?>一覧</p></div>
<div class="card-body">

<center>
<div class="col-2">
    <button type="button" class="btn btn-block btn-outline-secondary place_add_btn">
        <i class="fa fa-plus-circle"></i> 新規施設追加
    </button>
</div>
<br>
</center>

<div id="place_list">
<?php for ( $i = 0, $n = count ($place_list); $i < $n; $i ++ ) : ?>
<table class="table" id="place_<?= $place_list[$i]['id'] ?>">
  <tbody>
    <tr>
      <th width="4%" class="bg_c003"><button type="button" class="btn btn-block btn-info"><i class="fa fa-sort"></i> 移動</button></th>
      <td width="86%"><p class="h2"><?= $place_list[$i]['name'] ?></p></td>
      <td width="4%">
        <button type="button" class="btn btn-block btn-default place_edit_btn" data-id="<?= $place_list[$i]['id'] ?>">
            <i class="fa fa-edit"></i> 編集
        </button>
      </td>
      <td width="4%">
        <button type="button" class="btn btn-block btn-default place_del_btn" data-id="<?= $place_list[$i]['id'] ?>">
            <i class="fa fa-times-circle"></i> 削除
        </button>
      </td>
    </tr>
 </tbody>
</table>
<?php endfor ; ?>
</div>


</div>

<input type="hidden" id="place_id" name="place_id">
<input type="hidden" id="place_new" name="place_new">
</form>

	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>

<div id="dialog_edit" title="更新">
  <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      情報を更新します。<br>
      よろしいですか？
  </p>
</div>
<div id="dialog_del" title="削除">
  <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
      情報を削除します。<br>
      よろしいですか？
  </p>
</div>
<div id="info" title="info"></div>

</body>
</html>
