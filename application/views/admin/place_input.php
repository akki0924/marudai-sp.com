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
  <link href="<?= site_dir(); ?>css/admin/place.css" rel="stylesheet">
  <style>
  #info { display: none; }
  </style>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>
  <script type="text/javascript" src="<?= site_dir(); ?>js/admin/place_input_func.js"></script>
</head>
<body>

<!-- ヘッダー-->
<?= $header_tpl ?>
<!-- ヘッダー-->


<!-- CONTENTS -->

<div class="card-header"><p class="card-title"><?= (isset ( $form['name'] ) ? $form['name'] : '新規作成' ) ?></p></div>

<form action="<?=site_dir();?>admin/place/input" id="operation_form" name="operation_form" method="post" enctype="multipart/form-data" accept-charset="utf-8">

<div class="card-body">
    <?php if ( $place_id ) : ?>
    <div>
        <button type="button" class="btn btn-block btn-default col-2 float-left mr-2" onClick="location.href='<?=site_dir();?>admin/place'" disabled>
            <i class="fa fa-file-text-o"></i> 基本情報
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-block btn-default col-2 float-left mr-2" onClick="location.href='<?=site_dir();?>admin/topics'">
            <i class="fa fa-check-square-o"></i> TOPICS
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-block btn-default col-2 mb-3" onClick="location.href='<?=site_dir();?>admin/schedule'">
            <i class="fa fa-calendar"></i> スケジュール
        </button>
    </div>
    <br>
    <?php endif ;?>
    
    <div class="col-6 cont_center">
        <p class="card-title mb-4 text-center">基本情報</p>
        アカウントID
        <input type="text" class="form-control" placeholder="" id="account" name="account" value="<?= (isset ( $form['account'] ) ? $form['account'] : '' ) ?>">
<?= form_error('account'); ?>
        <br>
        
        パスワード
        <input type="text" class="form-control" placeholder="" id="password" name="password" value="<?= (isset ( $form['password'] ) ? $form['password'] : '' ) ?>">
<?= form_error('password'); ?>
        <br>
        
        名前
        <input type="text" class="form-control" placeholder="" id="name" name="name" value="<?= (isset ( $form['name'] ) ? $form['name'] : '' ) ?>">
<?= form_error('name'); ?>
        <br>
        
        タイプ
        <?= form_dropdown("type_id", $type_id_list, (isset($form['type_id']) ? $form['type_id'] : ""), 'id="type_id" class="form-control col-6"'); ?>
        <?= form_error('type_id'); ?>
        <br>
        
        住所
        <input type="text" class="form-control" placeholder="" id="address" name="address" value="<?= (isset ( $form['address'] ) ? $form['address'] : '' ) ?>">
<?= form_error('address'); ?>
        <a href="#" onclick="update_gmap_coords(); return false;">Google地図座標更新</a>
        <br><br>
        
        Lat / Long
        <div><input type="text" class="form-control float-left col-4 mr-2" placeholder="" id="lat" name="lat" value="<?= (isset ( $form['lat'] ) ? $form['lat'] : '' ) ?>"></div>
        <div><input type="text" class="form-control col-4" placeholder="" id="lng" name="lng" value="<?= (isset ( $form['lng'] ) ? $form['lng'] : '' ) ?>"></div>
<?= form_error('lat'); ?>
<?= form_error('lng'); ?>
        <br>
        
        画像
        <div class="imgInput">
<?= form_upload ( "place_img", "", "" ); ?>
<?php if ( isset ( $form['place_img_path'] ) && $form['place_img_path'] != '' ) : ?>
<img src="<?= $form['place_img_path'] ?>" alt="" class="imgView">
<?php elseif ( $place_img_exists ) : ?>
<img src="<?= site_dir ( "src/place_img/" . $place_id ) ?>" alt="" class="imgView">
<?php endif; ?>
        </div>
        <br>
        
        休館日
        <textarea class="form-control" id="closing" name="closing" rows="5"><?= (isset ( $form['closing'] ) ? $form['closing'] : '' ) ?></textarea>
<?= form_error('closing'); ?>
        <br>
        
        URL
        <input type="text" class="form-control" placeholder="" id="url" name="url" value="<?= (isset ( $form['url'] ) ? $form['url'] : '' ) ?>">
<?= form_error('url'); ?>
        <br>
        
        TEL
        <input type="text" class="form-control col-6 mb-5" placeholder="" id="tel" name="tel" value="<?= (isset ( $form['tel'] ) ? $form['tel'] : '' ) ?>">
<?= form_error('tel'); ?>
        
        <input type="submit" name='submit_conf_btn' class="cont_center btn btn-block btn-info col-3" value="決定">
        <input type="button" class="cont_center btn btn-block btn-default col-3 mb-2" value="戻る" onClick="location.href='<?=site_dir();?>admin/place'">
    </div>



</div>
<input type='hidden' id='place_img_path' name='place_img_path' value='<?= isset ( $form['place_img_path'] ) ? $form['place_img_path'] : '' ?>'>
</form>

    <div class="card-footer">
    <div class="col-12">copyright &copy; Nagoya art news</div>
    </div>

<div id="info" title="info"></div>


</body>
</html>
