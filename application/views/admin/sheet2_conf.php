<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>チェックシート2管理｜<?= $const['site_title_name'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style_admin.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>js/nav.js"></script>
<script>
$(function(){
	$('.back_btn').click(function() {
		$('#action').val( 'back' );
		$('#operation_form').submit();
	});
	$('.comp_btn').click(function() {
		$('#action').val( 'comp' );
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
			<div class="pc"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/index/logout" class="btn frame short">ログアウト</a></div>
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
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/admin">ログイン管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet1">チェックシート1管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet2">チェックシート2管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user">登録者管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/index/logout">ログアウト</a></li>
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
				<div class="col4"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/admin" class="btn mng">ログイン管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet1" class="btn mng">チェックシート1管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet2" class="btn mng frame">チェックシート2管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user" class="btn mng">登録者管理</a></div>
			</div>
		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20"><?= $exists ? 'チェックシート2情報編集' : '新規チェックシート2登録' ?></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin/sheet2/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
								<tr>
									<th><span class="required">ナンバー</span></th>
									<td><?= VarDisp($form['no']) ?></td>
								</tr>
								<tr>
									<th><span class="required">内容</span></th>
									<td><?= VarDisp($form['contents']) ?></td>
								</tr>
								<tr>
									<th><span class="required">点数</span></th>
									<td><?= VarDisp($form['point']) ?></td>
								</tr>
								<tr>
									<th><span class="required">表示ステータス</span></th>
									<td><?= VarDisp($form['status_name']) ?></td>
								</tr>
							</tbody>
						</table>

						<a class="btn mb_20 comp_btn"><?= $exists ? '更新' : '登録' ?></a>
						<a class="btn frame back_btn">戻る</a>
						<input type="hidden" name="id" id="id">
						<input type="hidden" name="action" id="action">
						<?php foreach ($form as $key => $val) { ?>
							<?= form_hidden($key, $val); ?>
						<?php } ?>
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

