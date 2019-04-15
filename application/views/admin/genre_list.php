<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ジャンル設定｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">

  <link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">
  <style>
  #dialog_del, #info { display: none; }
  
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
  
  </style>
  
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>
  
  <script>
  var genre_ajax_sort_url = "<?= site_dir(); ?>admin/genre/sort_part";
  var genre_ajax_del_url = "<?= site_dir(); ?>admin/genre/del_part";
  </script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/genre_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->
<form action="<?=site_dir();?>admin/genre/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">

<div class="card-header"><p class="card-title">ジャンル設定</p></div>
<div class="card-body">
	<div class="col-2 cont_center mb-5">
		<button type="button" class="btn btn-block btn-outline-secondary" onClick="location.href='<?=site_dir();?>admin/genre/input'"><i class="fa fa-plus-circle"></i> 新規ジャンル追加</button></div>
		
	<div id="genre_list" class="col-8 cont_center">
<?php for ( $i = 0, $n = count ($genre_list); $i < $n; $i ++ ) : ?>
		<table id="genre_<?= $genre_list[$i]['id'] ?>" class="table">
		  <tbody>
		    <tr>
			  <th width="4%" class="bg_c003"><button type="button" class="btn btn-block btn-info"><i class="fa fa-sort"></i> 移動</button></th>
              <td width="86%"><p><?= $genre_list[$i]['name'] ?></p></td>
		      <td width="4%"><button type="button" class="btn btn-block btn-default genre_edit_btn" data-id="<?= $genre_list[$i]['id'] ?>"><i class="fa fa-edit"></i> 編集</button></td>
			  <td width="4%"><button type="button" class="btn btn-block btn-default genre_del_btn" data-id="<?= $genre_list[$i]['id'] ?>"><i class="fa fa-times-circle"></i> 削除</button></td>
		    </tr>
		  </tbody>
		</table>
<?php endfor ; ?>

	</div>


</div>

<input type="hidden" id="target_id" name="target_id">
</form>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
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
