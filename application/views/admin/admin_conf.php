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
					<?php if ($exists) { ?>
						<p class="font_20 bold mb_40">利用者ID：<?= VarDisp($form['id']) ?></p>
					<?php } ?>
					<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin/admin/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['id']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['account']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['password']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['company_id']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['authority']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['name']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['status_name']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['regist_date']) ?></td>
								</tr>
								<tr>
									<th><span class="required"></span></th>
									<td><?= VarDisp($form['edit_date']) ?></td>
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

