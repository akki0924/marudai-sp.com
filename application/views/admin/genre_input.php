<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ジャンル編集｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">

  <link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">
  <style>
  #info { display: none; }
  </style>
  
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->
<form action="<?=site_dir();?>admin/genre/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">

<div class="card-header"><p class="card-title">ジャンル設定</p></div>
<div class="card-body">
	<div class="col-8 cont_center"><p class="card-title mb-4 text-center">ジャンル編集</p></div>
	
	<div class="col-6 cont_center">
		タイトル<input type="input" class="form-control mb-5" placeholder="" name="name" value="<?= (isset ( $form['name'] ) ? $form['name'] : '' ) ?>">
		<?= form_error('name'); ?>
        <br>
		
		<input type="submit" class="cont_center btn btn-block btn-info col-3" name="submit_conf_btn" value="保存">
		<input type="button" class="cont_center btn btn-block btn-default col-3 mb-2" value="戻る" onClick="location.href='<?=site_dir();?>admin/genre'">
	</div>


</div>
<input type='hidden' id='target_id' name='target_id' value='<?= isset ( $target_id ) ? $target_id : '' ?>'>
</form>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>


</body>
</html>
