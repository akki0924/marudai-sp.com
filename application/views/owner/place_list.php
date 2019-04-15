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
#dialog_del, #info { display: none; }

#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
#sortable li span { position: absolute; margin-left: -1.3em; }

</style>

<script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>

<script type="text/javascript" src="<?= site_dir(); ?>js/scripts.js"></script>
<script>
var place_ajax_sort_url = "<?= site_dir(); ?>owner/place/sort_part";
var place_ajax_del_url = "<?= site_dir(); ?>owner/place/del_part";
</script>
<script type="text/javascript" src="<?= site_dir(); ?>js/place_func.js"></script>


</head>

<body>

<!-- header -->
<?= $header_tpl ?>
<!-- /header -->

<div class="container">

<form action="<?=site_dir();?>owner/place/input" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">

<input type="button" value="新規追加" class="place_add_btn">

<ul id="place_list">
<?php for ( $i = 0, $n = count ($place_list); $i < $n; $i ++ ) : ?>
  <li id="place_<?= $place_list[$i]['id'] ?>" class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <?= $place_list[$i]['name'] ?>
    <input type="button" value="編集" class="place_edit_btn" data-id="<?= $place_list[$i]['id'] ?>">
    <input type="button" value="削除" class="place_del_btn" data-id="<?= $place_list[$i]['id'] ?>">
  </li>
<?php endfor ; ?>
</ul>



<div id="footer" style="top: 94px; position: relative;">

<center>
<font color="#FFFFFF">Copyrightc 2017 Inax Corporation All Rights Reserved.</font>
<center>

</div>

<input type="hidden" id="place_id" name="place_id">
</form>

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
