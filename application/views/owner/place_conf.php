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

</head>

<body>

<!-- header -->
<?= $header_tpl ?>
<!-- /header -->

<div class="container">

<form action="<?=site_dir();?>owner/place/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">
<center>
名前:<?= $form['name'] ?>
<br>

タイプ:<?= $form['type_id'] ?>
<br>

住所:<?= $form['address'] ?>
<br>

Lat:<?= $form['lat'] ?>
<br>

Lng:<?= $form['lng'] ?>
<br>

画像:
<?php if ( isset ( $form['place_img_path'] ) && $form['place_img_path'] != '' ) : ?>
<img src="<?= $form['place_img_path'] ?>" alt="" class="imgView">
<?php elseif ( $place_img_exists ) : ?>
<img src="<?= site_dir ( "src/place_img/" . $place_id ) ?>" alt="" class="imgView">
<?php endif; ?>
<br>

休館日:<?= $form['closing'] ?>
<br>

URL:<?= $form['url'] ?>
<br>

TEL:<?= $form['tel'] ?>
<br>

</center>
<br>
<br>
<center>
<form action="<?=site_dir();?>owner/place/input" id="back_form" name="back_form" method="post" accept-charset="utf-8">
<input type="submit" name='submit_input_btn' value="戻る">
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
<?= form_hidden ($form); ?>
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
<input type='hidden' id='place_img_path' name='place_img_path' value='<?= isset ( $form['place_img_path'] ) ? $form['place_img_path'] : '' ?>'>
</form>
<form action="<?=site_dir();?>owner/place/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">
<input type="submit" name='submit_comp_btn' value="登録">
<input type='hidden' id='place_id' name='place_id' value='<?= isset ( $place_id ) ? $place_id : '' ?>'>
<input type='hidden' id='place_img_path' name='place_img_path' value='<?= isset ( $form['place_img_path'] ) ? $form['place_img_path'] : '' ?>'>
<input type='hidden' id='confFlg' name='confFlg' value='1'>
<?= form_hidden ($form); ?>
</form>
</center>



<div id="footer" style="top: 94px; position: relative;">

<center>
<font color="#FFFFFF">Copyrightc 2017 Inax Corporation All Rights Reserved.</font>
<center>

</div>
<div id="info" title="info"></div>

</div>

</body>
</html>
