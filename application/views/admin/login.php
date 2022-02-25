<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>管理者ログイン｜バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<link rel="stylesheet" type="text/css" href="<?= SiteDir(); ?>form/css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script language="JavaScript">
$(function() {
	$('.login_btn').click(function() {
		$('#action').val('login');
		$('#operation_form').submit();
	});
});
</script>
</head>

<body>
<header>
	<div class="container">
		<div class="logo mb_60"><img src="<?= SiteDir(); ?>img/logo.gif" alt="ロゴ"></div>
	</div>
</header>


<main>
	<div class="container">
		<p class="font_24 txt_center bold mb_60">計量記録管理システム</p>
		<form id="operation_form" name="operation_form" action="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>" method="post" class="max360">
			<p class="mb_10">ユーザーID</p>
			<input type="text" name="account" class="mb_20" value="<?= VarDisp($form['account']) ?>">
			<p class="mb_10">パスワード</p>
			<input type="password" name="password" class="mb_40" value="<?= VarDisp($form['password']) ?>">
			<?php if (form_error('account')) : ?>
			<span class="text-danger"><?= form_error('account'); ?></span><br>
			<?php endif; ?>
			<?php if (form_error('password')) : ?>
			<span class="text-danger"><?= form_error('password'); ?></span><br>
			<?php endif; ?>
			<a class="btn login_btn size_m"><span class="icon_login">ログイン</span></a>
			<input type="hidden" name="action" id="action">
			<input type="hidden" name="login" value="1">
		</form>
	</div>

</main>


</body>
</html>
