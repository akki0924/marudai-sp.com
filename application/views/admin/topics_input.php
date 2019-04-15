<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>新規TOPICS追加｜Nagoya art news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/style.css">
  <link rel="stylesheet" href="<?= site_dir(); ?>dist/css/adminlte.min.css">
  
  <link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">
  <link href="<?= site_dir(); ?>css/admin/topics.css" rel="stylesheet">
  <style>
  #info { display: none; }
  </style>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/topics_input_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title">あいちトリエンナーレ実行委員会</p></div>

<form action="<?=site_dir();?>admin/topics/input" id="operation_form" name="operation_form" method="post" enctype="multipart/form-data" accept-charset="utf-8">

<div class="card-body">
	<div class="col-6 cont_center">
		<p class="card-title mb-4 text-center">新規TOPICS追加</p>
		タイプ
        　
		<?php for ( $paper_i = 1, $n = count($paper_type_list); $paper_i <= $n; $paper_i ++ ) : ?>
			<?= form_radio( "paper_type", $paper_i, ( isset ( $form['paper_type'] ) && $form['paper_type'] == $paper_i ? true : false ), "id=paper_type" . "_" . $paper_i ); ?>
            <?= form_label( $paper_type_list[$paper_i], "paper_type" . "_" . $paper_i, "class='mr-4'" ); ?>
		<?php endfor; ?>
        <?= form_error('paper_type'); ?>
        <br>
        <br>
        
		タイトル
        <input type="text" class="form-control" placeholder="" id="title" name="title" value="<?= (isset ( $form['title'] ) ? $form['title'] : '' ) ?>">
        <?= form_error('title'); ?>
        <br>
        
		サブタイトル
        <input type="text" class="form-control" placeholder="" id="title_sub" name="title_sub" value="<?= (isset ( $form['title_sub'] ) ? $form['title_sub'] : '' ) ?>">
        <?= form_error('title_sub'); ?>
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
        
		展覧会内容
        <textarea class="form-control" rows="8" id="body" name="body"><?= (isset ( $form['body'] ) ? $form['body'] : '' ) ?></textarea>
        <?= form_error('body'); ?>
        <br>
        
		次回開催情報
        <textarea class="form-control" rows="8" id="next" name="next"><?= (isset ( $form['next'] ) ? $form['next'] : '' ) ?></textarea>
        <?= form_error('next'); ?>
        <br>
        
		備考
        <textarea class="form-control" rows="5" id="memo" name="memo"><?= (isset ( $form['memo'] ) ? $form['memo'] : '' ) ?></textarea>
        <?= form_error('memo'); ?>
        <br>
        
        画像
        <div class="imgInput">
<?= form_upload ( "topics_img", "", "" ); ?>
<?php if ( isset ( $form['topics_img_path'] ) && $form['topics_img_path'] != '' ) : ?>
<img src="<?= $form['topics_img_path'] ?>" alt="" class="imgView">
<?php elseif ( $topics_img_exists ) : ?>
<img src="<?= site_dir ( "src/topics_img/" . $topics_id ) ?>" alt="" class="imgView">
<?php endif; ?>
        </div>
        <br>
		画像キャプション
        <input type="text" class="form-control mb-5" placeholder="" id="caption" name="caption" value="<?= (isset ( $form['caption'] ) ? $form['caption'] : '' ) ?>">
        <?= form_error('caption'); ?>
        <br>
		
        <input type="submit" name='submit_conf_btn' class="cont_center btn btn-block btn-info col-3" value="決定">
        <input type="button" class="cont_center btn btn-block btn-default col-3 mb-2" value="戻る" onClick="location.href='<?=site_dir();?>admin/topics'">
	</div>



</div>
<input type='hidden' id='topics_id' name='topics_id' value='<?= isset ( $topics_id ) ? $topics_id : '' ?>'>
<input type='hidden' id='topics_img_path' name='topics_img_path' value='<?= isset ( $form['topics_img_path'] ) ? $form['topics_img_path'] : '' ?>'>
</form>


	<div class="card-footer">
	<div class="col-12">copyright &copy; Nagoya art news</div>
	</div>

<div id="info" title="info"></div>

</body>
</html>
