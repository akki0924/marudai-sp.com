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
#dialog_edit, #dialog_del, #info { display: none; }

#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
#sortable li span { position: absolute; margin-left: -1.3em; }

</style>

<script type="text/javascript" src="<?= site_dir(); ?>js/jquery.min.1.12.4.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>js/datepicker-ja.js"></script>

<script type="text/javascript" src="<?= site_dir(); ?>js/scripts.js"></script>
<script>
var publication_ajax_url = "<?= site_dir(); ?>owner/main/publication_part";
var topics_ajax_sort_url = "<?= site_dir(); ?>owner/main/topics_sort_part";
var topics_ajax_edit_url = "<?= site_dir(); ?>owner/main/topics_edit_part";
var topics_ajax_del_url = "<?= site_dir(); ?>owner/main/topics_del_part";
</script>
<script type="text/javascript" src="<?= site_dir(); ?>js/main_func.js"></script>


</head>

<body>

<!-- header -->
<?= $header_tpl ?>
<!-- /header -->

<div class="container">

<form action="<?=site_dir();?>admin/item" id="operation_form" name="operation_form" method="post" accept-charset="utf-8">
<center>
現在刊行情報
No.:
<input type="text" id="no" name="no" value="<?= (isset ( $form['no'] ) ? $form['no'] : '' ) ?>">
<br>
日付範囲:
<input type="text" id="start" name="start" value="<?= (isset ( $form['start'] ) ? $form['start'] : '' ) ?>" class="datepicker">
～
<input type="text" id="end" name="end" value="<?= (isset ( $form['end'] ) ? $form['end'] : '' ) ?>" class="datepicker">
</center>
<br>
<br>
<center>
<input type="button" id="publication_update_btn" value="更新">
</center>
</form>

<ul id="topics_list">
<?php for ( $i = 0, $n = count ($topics_list); $i < $n; $i ++ ) : ?>
  <li id="topics_<?= $topics_list[$i]['id'] ?>" class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <?= $topics_list[$i]['place_name'] ?>
    <input type="button" value="編集" onClick="location.href='http://google.co.jp'">
    <input type="button" value="削除" class="topics_del_btn" data-id="<?= $topics_list[$i]['id'] ?>">
  </li>
<?php endfor ; ?>
</ul>



<div id="footer" style="top: 94px; position: relative;">

<center>
<font color="#FFFFFF">Copyrightc 2017 Inax Corporation All Rights Reserved.</font>
<center>

</form>

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
