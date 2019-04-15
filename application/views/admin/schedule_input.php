<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>新規スケジュール追加｜Nagoya art news</title>
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
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/schedule_input_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">あいちトリエンナーレ実行委員会</p></div>

<form action="<?=site_dir();?>admin/schedule/input" id="operation_form" name="operation_form" method="post" enctype="multipart/form-data" accept-charset="utf-8">

<div class="card-body">
	<div class="col-6 cont_center">
		<p class="card-title mb-4 text-center">新規スケジュール追加</p>
		タイトル
        <input type="text" class="form-control" placeholder="" id="title" name="title" value="<?= (isset ( $form['title'] ) ? $form['title'] : '' ) ?>">
        <br>
        
		開始日
        <input type="text" class="form-control datepicker" placeholder="" id="start" name="start" value="<?= (isset ( $form['start'] ) ? $form['start'] : '' ) ?>">
        <?= form_error('start'); ?>
		<br>
        
		終了日
        <input type="text" class="form-control datepicker" placeholder="" id="end" name="end" value="<?= (isset ( $form['end'] ) ? $form['end'] : '' ) ?>">
        <?= form_error('end'); ?>
		<br>
        
		休館日
        <textarea class="form-control" rows="5" id="closing" name="closing"><?= (isset ( $form['closing'] ) ? $form['closing'] : '' ) ?></textarea>
        <?= form_error('closing'); ?>
        <br>
        
		ジャンル<br>
        <?php for ( $i = 0, $n = count ( $genre_list ); $i < $n; $i ++ ) : ?>
            <?= form_checkbox ( "genre[]", $genre_list[$i]['id'], ( isset ( $form['genre'] ) && in_array ( $genre_list[$i]['id'], $form['genre'] ) ? true : false ), "id=genre_" . $genre_list[$i]['id'] ); ?><?= form_label ( $genre_list[$i]['name'], "genre_" . $genre_list[$i]['id'] ); ?>
        <br>
        <?php endfor; ?>
        <br>
		
        <input type="submit" name='submit_conf_btn' class="cont_center btn btn-block btn-info col-3" value="決定">
        <input type="button" class="cont_center btn btn-block btn-default col-3 mb-2" value="戻る" onClick="location.href='<?=site_dir();?>admin/schedule'">
	</div>



</div>

<input type='hidden' id='schedule_id' name='schedule_id' value='<?= isset ( $schedule_id ) ? $schedule_id : '' ?>'>
<input type='hidden' id='schedule_img_path' name='schedule_img_path' value='<?= isset ( $form['schedule_img_path'] ) ? $form['schedule_img_path'] : '' ?>'>
</form>

	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>


</body>
</html>
