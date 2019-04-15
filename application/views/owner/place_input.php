<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="assets/img/favicon.png">
<title>test</title>

<!-- Bootstrap core CSS -->
<link href="<?= site_dir(); ?>css/bootstrap.css" rel="stylesheet">
<link href="<?= site_dir(); ?>css/style.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="<?= site_dir(); ?>css/main.css" rel="stylesheet">

<link href="<?= site_dir(); ?>css/jquery-ui.min.css" rel="stylesheet">


<!-- Fonts from Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
<style>
#info { display: none; }
</style>

<script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>

<script type="text/javascript" src="<?= site_dir(); ?>js/scripts.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/place_input_func.js"></script>

</head>

<body>

<!-- header -->
<?= $header_tpl ?>
<!-- /header -->

<div class="container">

<form action="<?=site_dir();?>owner/place/input" id="select_form" name="select_form" method="post" accept-charset="utf-8">
<ul class="submenu">
<li>
<input type="button" value="基本情報" class="select_btn" data-action="<?=site_dir();?>owner/place/input">
</li>
<li>
<input type="button" value="TOPICS" class="select_btn" data-action="<?=site_dir();?>owner/topics">
</li>
<li>
<input type="button" value="スケジュール" class="select_btn" data-action="<?=site_dir();?>owner/schedule">
</li>
</ul>
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
</form>


<form action="<?=site_dir();?>owner/place/input" id="operation_form" name="operation_form" method="post" enctype="multipart/form-data" accept-charset="utf-8">
<center>
アカウント:
<input type="text" id="account" name="account" value="<?= (isset ( $form['account'] ) ? $form['account'] : '' ) ?>">
<?= form_error('account'); ?>
<br>
パスワード:
<input type="text" id="password" name="password" value="<?= (isset ( $form['password'] ) ? $form['password'] : '' ) ?>">
<?= form_error('password'); ?>
<br>
名前:
<input type="text" id="name" name="name" value="<?= (isset ( $form['name'] ) ? $form['name'] : '' ) ?>">
<?= form_error('name'); ?>
<br>
タイプ:
<?= form_dropdown("type_id", $type_id_list, (isset($form['type_id']) ? $form['type_id'] : ""), 'id="type_id"'); ?>
<?= form_error('type_id'); ?>
<br>
住所:
<input type="text" id="address" name="address" value="<?= (isset ( $form['address'] ) ? $form['address'] : '' ) ?>">
<?= form_error('address'); ?>
<br>

Lat:
<input type="text" id="lat" name="lat" value="<?= (isset ( $form['lat'] ) ? $form['lat'] : '' ) ?>">
<?= form_error('lat'); ?>
<br>
Lng:

<input type="text" id="lng" name="lng" value="<?= (isset ( $form['lng'] ) ? $form['lng'] : '' ) ?>">
<?= form_error('lng'); ?>
<br>

画像:
<div class="imgInput">
<?= form_upload ( "place_img", "", "" ); ?>
<br>
<?php if ( isset ( $form['place_img_path'] ) && $form['place_img_path'] != '' ) : ?>
<img src="<?= $form['place_img_path'] ?>" alt="" class="imgView">
<?php elseif ( $place_img_exists ) : ?>
<img src="<?= site_dir ( "src/place_img/" . $place_id ) ?>" alt="" class="imgView">
<?php endif; ?>
</div>
<br>

休館日:
<input type="text" id="closing" name="closing" value="<?= (isset ( $form['closing'] ) ? $form['closing'] : '' ) ?>">
<?= form_error('closing'); ?>
<br>

URL:
<input type="text" id="url" name="url" value="<?= (isset ( $form['url'] ) ? $form['url'] : '' ) ?>">
<?= form_error('url'); ?>
<br>

TEL:
<input type="text" id="tel" name="tel" value="<?= (isset ( $form['tel'] ) ? $form['tel'] : '' ) ?>">
<?= form_error('tel'); ?>
<br>

</center>
<br>
<br>
<center>
<input type="button" value="キャンセル" onClick="location.href='<?=site_dir();?>owner/place'">
<input type="submit" name='submit_conf_btn' value="確認">
</center>
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
<input type='hidden' id='place_img_path' name='place_img_path' value='<?= isset ( $form['place_img_path'] ) ? $form['place_img_path'] : '' ?>'>
</form>



<div id="footer" style="top: 94px; position: relative;">

<center>
<font color="#FFFFFF">Copyrightc 2017 Inax Corporation All Rights Reserved.</font>
<center>

</div>
<div id="info" title="info"></div>

</div>

</body>
</html>
