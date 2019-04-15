<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="assets/img/favicon.png">
<title>技術情報閲覧サイト</title>

<!-- Bootstrap core CSS -->
<link href="<?= site_dir(); ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?= site_dir(); ?>/css/style.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="<?= site_dir(); ?>/css/main.css" rel="stylesheet">

<!-- Fonts from Google Fonts -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?= site_dir(); ?>/js/scripts.js"></script>
</head>

<body>


<div class="container">
<form class="form-inline" action="<?=site_dir("admin/index");?>" role="form" method="post">
<div class="event-title3-right">技術資料閲覧サイト</div>
<div class="event-title4-right"><font color="#FFFFFF">テクニカルデータセンター</font></div>

<h2><font color="#FFFFFF">管理ページログイン</font></h2>
<table border="0" cellpadding="20" bgcolor="#FFFFFF">
  <tbody>

    <tr>
      <th width="200" bgcolor="#FFFFFF">ユーザーID</th>
      <td bgcolor="#FFFFFF">
        <input type="text" name="account" value="<?=$form['account'];?>" placeholder="ユーザーID">
        <?php if (form_error('account')) : ?>
        <br>
        <span class="errors"><?= form_error('account'); ?></span><br>
        <br>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th bgcolor="#FFFFFF">パスワード</th>
      <td bgcolor="#FFFFFF">
        <input type="password" name="password" value="<?=$form['password'];?>" placeholder="パスワード">
        <?php if (form_error('password')) : ?>
        <br><br>
        <span class="errors"><?= form_error('password'); ?></span><br>
        <br>
        <?php endif; ?>
        <?php if (isset ($error_msg)) : ?>
        <br><br>
        <span class="errors"><?= $error_msg ?></span>
        <br>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <br>
        <input type="submit" name="submit_btn" value="ログイン" class="btn btn-warning btn-lg w250">
      </td>
    </tr>

  </tbody>
</table>

</form>

<div id="footer" style="top: 94px; position: relative;">

<center>
Copyrightc 2017 Inax Corporation All Rights Reserved.
<center>

</div>

<!-- /container --> 

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> 
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
