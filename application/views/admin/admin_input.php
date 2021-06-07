<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ログイン管理｜<?= $const['site_title_name'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style_admin.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>js/nav.js"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>js/yubinbango.js"></script>
<script>
$(function(){
	$('.conf_btn').click(function() {
		$('#action').val( 'conf' );
		$('#operation_form').submit();
	});

});
</script>
</head>

<body>
<header class="mng_head">
	<div class="container">
		<div class="row align_center">
			<p class="font_20"><?= $const['site_title_name'] ?></p>
			<div class="pc"><a href="<?= SiteDir(); ?>admin/index/logout" class="btn frame short">ログアウト</a></div>
		</div><!--/.row-->
	</div><!--/.container-->

	<!-- sp menu ----------------------------->
	<div class="navToggle sp">
	<span></span>
	<span></span>
	<span></span>
	</div>

	<nav class="globalMenuSp sp">
		<ul>
			<li><a href="<?= SiteDir(); ?>admin/admin">ログイン管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/pref">都道府県管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/sheet1">チェックシート1管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/sheet2">チェックシート2管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/index/logout">ログアウト</a></li>
		</ul>
	</nav>
	<!-- /sp menu ---------------------------->
</header>

<main>
	<div class="mng_top">
		<div class="container">
			<h1>管理者画面</h1>
		</div><!--/.container-->
	</div>

	<section id="management">
		<div class="container">
			<div class="row">
				<div class="col4"><a href="<?= SiteDir(); ?>admin/admin" class="btn mng frame">ログイン管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/pref" class="btn mng">都道府県管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/sheet1" class="btn mng">チェックシート1管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/sheet2" class="btn mng">チェックシート2管理</a></div>
			</div>
		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20"><?= $exists ? 'ログイン情報編集' : '新規ログイン登録' ?></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin/admin/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="text" name="id" value="<?= VarDisp($form['id']) ?>" class="half">
										<?php if (form_error('id')) : ?>
											<span><?= form_error('id'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="text" name="account" value="<?= VarDisp($form['account']) ?>" class="half">
										<?php if (form_error('account')) : ?>
											<span><?= form_error('account'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="text" name="password" value="<?= VarDisp($form['password']) ?>" class="half">
										<?php if (form_error('password')) : ?>
											<span><?= form_error('password'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="number" name="company_id" value="<?= VarDisp($form['company_id']) ?>" class="half">
										<?php if (form_error('company_id')) : ?>
											<span><?= form_error('company_id'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="number" name="authority" value="<?= VarDisp($form['authority']) ?>" class="half">
										<?php if (form_error('authority')) : ?>
											<span><?= form_error('authority'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<input type="text" name="name" value="<?= VarDisp($form['name']) ?>" class="half">
										<?php if (form_error('name')) : ?>
											<span><?= form_error('name'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<div class="select size_m">
											<?= form_dropdown("status", $select['status'], (isset($form['status']) ? $form['status'] : ""), 'id="status"'); ?>
										</div>
										<?php if (form_error('status')) : ?>
											<span><?= form_error('status'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<?php if (form_error('regist_date')) : ?>
											<span><?= form_error('regist_date'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td>
										<?php if (form_error('edit_date')) : ?>
											<span><?= form_error('edit_date'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						</table>

						<a class="btn mb_20 conf_btn">確認</a>
						<a href="<?= SiteDir(); ?>admin/admin" class="btn frame">キャンセル</a>
						<input type="hidden" name="id" value="<?= (isset($form['id']) ? $form['id'] : '') ?>">
						<input type="hidden" name="action" id="action">
					</form>
				</div><!--./bg_gray-->

			</div><!--./max680-->
		</div><!--./container-->
	</section>
</main>

<footer>
	<div class="container">
		<p class="copy">Copyright &copy;<?= $const['copyright_name'] ?> All Rights Reserved</p>
	</div><!--./container-->
</footer>

</body>
</html>

