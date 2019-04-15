<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>美術館一覧｜Nagoya art news</title>
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
  <script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>
  <script>
  var publication_ajax_url = "<?= site_dir(); ?>admin/main/publication_part";
  var topics_ajax_sort_url = "<?= site_dir(); ?>admin/main/topics_sort_part";
  var topics_ajax_edit_url = "<?= site_dir(); ?>admin/main/topics_edit_part";
  var topics_ajax_del_url = "<?= site_dir(); ?>admin/main/topics_del_part";
  </script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/main_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->
<div class="card-body">
	<div class="row">
    
    <form action="<?=site_dir();?>admin/publication" id="publication_form" name="publication_form" method="post" accept-charset="utf-8">
	<div class="col-md-6 row">
	<p class="card-title">現在刊行情報</p>

	<div class="col-4">
<i class="fa fa-newspaper-o"></i>
NO.
<input type="input" class="form-control" placeholder="" id="no" name="no" value="<?= (isset ( $publication['no'] ) ? $publication['no'] : '' ) ?>"><i class="fa fa-calendar"></i>
日付範囲
<input type="input" class="form-control datepicker" placeholder="" id="start" name="start" value="<?= (isset ( $publication['start_disp'] ) ? $publication['start_disp'] : '' ) ?>">
～
<input type="input" class="form-control datepicker" placeholder="" id="end" name="end" value="<?= (isset ( $publication['end_disp'] ) ? $publication['end_disp'] : '' ) ?>">
	</div>
	<button type="button" class="btn btn-block btn-info col-1" id="publication_update_btn">保存</button>
	</div>
    </form>

	<div class="col-md-6">
	<button type="button" class="btn btn-block btn-default col-3 float-right" onClick="location.href='<?=site_dir();?>admin/genre'"><i class="fa fa-cog"></i> ジャンル設定</button>
	</div>


	</div>
</div>



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
