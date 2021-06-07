<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>管理者ログイン｜<?= $const['site_title_name'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style_admin.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script language="JavaScript">
$(function() {
	$('.login_btn').click(function() {
			$('#action').val( 'login' );
		$('#operation_form').submit();
	});
});
</script>
</head>

<body>
<header>
	<div class="container">
		<div class="row align_center">
			<div class="col3_2 row just_start align_center">
				<a href="index.html" class="logo"><?= $const['site_title_name'] ?></a>
			</div>
		</div>
	</div><!--/.container-->
</header>

<main class="pb_non">
	<section id="login">
		<h1 class="mb_40">管理者ログイン</h1>
		<div class="bg_gray pd60">
			<div class="container">
				<div class="max680">
					<form id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin" method="post" class="white_boad mb_40">
						<div class="max480">
							<table class="mb_20">
								<tbody>
									<tr>
										<th><span class="required">アカウント</span></th>
										<td><input type="tel" name="account" value="<?= VarDisp($form['account']) ?>" class="<?= ((isset($error_account) && $error_account) ? 'formerr_color' : '') ?>"></td>
									</tr>
									<tr>
									<th><span class="required">パスワード</span></th>
									<td><input type="password" name="password"  value="<?= VarDisp($form['password']) ?>"  class="<?= ((isset($error_password) && $error_password) ? 'formerr_color' : '') ?>"></td>
									</tr>
								</tbody>
							</table>
							<?php if (form_error('account')) : ?>
							<span class="text-danger"><?= form_error('account'); ?></span><br>
							<?php endif; ?>
							<?php if (form_error('password')) : ?>
							<span class="text-danger"><?= form_error('password'); ?></span><br>
							<?php endif; ?>

							<a class="btn mb_20 login_btn">ログイン</a>
							<input type="hidden" name="action" id="action">
							<input type="hidden" name="login" value="1">
						</div>
					</form>
				</div>
			</div><!--./container-->
		</div><!--./bg_gray-->
	</section>
</main>

<footer>
	<div class="container">
		<p class="copy">Copyright &copy; All Rights Reserved</p>
	</div><!--./container-->
</footer>

</body>
</html>
