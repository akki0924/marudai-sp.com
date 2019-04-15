<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= $title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= site_dir(); ?>/css/style.css">
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?= site_dir(); ?>/js/openclose.js"></script>
</head>

<body>

<div id="container">


<form class="form-inline" action="<?= site_dir(); ?>owner/index" role="form" method="post">

<div id="contents">

<div id="main_select">

<section>

<h2>ログイン</h2>

<div class="select">
<table>
<tr>
<th>ユーザーID</th>
<td>
    <input type="text" name="account" value="<?=$form['account'];?>" placeholder="ユーザーID" class="login">
    <?php if (form_error('account')) : ?>
    <br>
    <span class="errors"><?= form_error('account'); ?></span><br>
    <br>
    <?php endif; ?>
</td>
</tr>
<tr>
<th>パスワード</th>
<td>
    <input type="password" name="password" value="<?=$form['password'];?>" placeholder="パスワード" class="login">
    <?php if (form_error('password')) : ?>
    <br><br>
    <span class="errors"><?= form_error('password'); ?></span><br>
    <br>
    <?php endif; ?>
</td>
</tr>
</table>
<center>
    <input type="submit" name="submit_btn" value="ログイン">
</center>
<?php if (form_error('login')) : ?>
<p><span class="errors"><?= form_error('login'); ?></span></p>
<?php endif; ?>

</div>

</section>

</div>
<!--/main_select-->

<p id="pagetop"><a href="#">↑ PAGE TOP</a></p>

</div>
<!--/contents-->

</div>
<!--/container-->

</form>

<!--メニューの３本バー-->
<div id="menubar_hdr" class="close"><span></span><span></span><span></span></div>
<!--メニューの開閉処理条件設定　800px以下-->
<script type="text/javascript">
if (OCwindowWidth() <= 800) {
	open_close("menubar_hdr", "menubar-s");
}
</script>
</body>
</html>
