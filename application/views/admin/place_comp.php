<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>基本情報｜Nagoya art news</title>
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
  <script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">基本情報</p></div>
<div class="card-body">
	<div class="col-8 cont_center"><p class="card-title mb-4 text-center">基本情報編集</p></div>
	
	<div class="col-6 cont_center">
		完了しました
		<button type="submit" class="cont_center btn btn-block btn-default col-3 mb-2" onClick="location.href='<?=site_dir();?>admin/place'">一覧に戻る</button>
	</div>


</div>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>


</body>
</html>
