<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登録者管理｜<?= $const['site_title_name'] ?></title>
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
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/create_log">自動生成ログ管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/pref">都道府県管理</a></li>
			<li><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/school">小学校管理</a></li>
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
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/create_log" class="btn mng">自動生成ログ管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/pref" class="btn mng">都道府県管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/school" class="btn mng">小学校管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet1" class="btn mng">チェックシート1管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet2" class="btn mng">チェックシート2管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user" class="btn mng frame">登録者管理</a></div>
			</div>
		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20"><?= $exists ? '登録者情報編集' : '新規登録者登録' ?></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
								<tr>
									<th><span class="required">エコアップID</span></th>
									<td>
										<input type="text" name="eco_id" value="<?= VarDisp($form['eco_id']) ?>" class="half">
										<?php if (form_error('eco_id')) : ?>
											<span><?= form_error('eco_id'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">ニックネーム</span></th>
									<td>
										<input type="text" name="nickname" value="<?= VarDisp($form['nickname']) ?>" class="half">
										<?php if (form_error('nickname')) : ?>
											<span><?= form_error('nickname'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_1" value="<?= VarDisp($form['sheet1_1_1']) ?>" class="half">
										<?php if (form_error('sheet1_1_1')) : ?>
											<span><?= form_error('sheet1_1_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_2" value="<?= VarDisp($form['sheet1_1_2']) ?>" class="half">
										<?php if (form_error('sheet1_1_2')) : ?>
											<span><?= form_error('sheet1_1_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_3" value="<?= VarDisp($form['sheet1_1_3']) ?>" class="half">
										<?php if (form_error('sheet1_1_3')) : ?>
											<span><?= form_error('sheet1_1_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_4" value="<?= VarDisp($form['sheet1_1_4']) ?>" class="half">
										<?php if (form_error('sheet1_1_4')) : ?>
											<span><?= form_error('sheet1_1_4'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_5" value="<?= VarDisp($form['sheet1_1_5']) ?>" class="half">
										<?php if (form_error('sheet1_1_5')) : ?>
											<span><?= form_error('sheet1_1_5'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_6" value="<?= VarDisp($form['sheet1_1_6']) ?>" class="half">
										<?php if (form_error('sheet1_1_6')) : ?>
											<span><?= form_error('sheet1_1_6'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_1_7" value="<?= VarDisp($form['sheet1_1_7']) ?>" class="half">
										<?php if (form_error('sheet1_1_7')) : ?>
											<span><?= form_error('sheet1_1_7'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_1" value="<?= VarDisp($form['sheet1_2_1']) ?>" class="half">
										<?php if (form_error('sheet1_2_1')) : ?>
											<span><?= form_error('sheet1_2_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_2" value="<?= VarDisp($form['sheet1_2_2']) ?>" class="half">
										<?php if (form_error('sheet1_2_2')) : ?>
											<span><?= form_error('sheet1_2_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_3" value="<?= VarDisp($form['sheet1_2_3']) ?>" class="half">
										<?php if (form_error('sheet1_2_3')) : ?>
											<span><?= form_error('sheet1_2_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_4" value="<?= VarDisp($form['sheet1_2_4']) ?>" class="half">
										<?php if (form_error('sheet1_2_4')) : ?>
											<span><?= form_error('sheet1_2_4'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_5" value="<?= VarDisp($form['sheet1_2_5']) ?>" class="half">
										<?php if (form_error('sheet1_2_5')) : ?>
											<span><?= form_error('sheet1_2_5'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_6" value="<?= VarDisp($form['sheet1_2_6']) ?>" class="half">
										<?php if (form_error('sheet1_2_6')) : ?>
											<span><?= form_error('sheet1_2_6'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_2_7" value="<?= VarDisp($form['sheet1_2_7']) ?>" class="half">
										<?php if (form_error('sheet1_2_7')) : ?>
											<span><?= form_error('sheet1_2_7'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_1" value="<?= VarDisp($form['sheet1_3_1']) ?>" class="half">
										<?php if (form_error('sheet1_3_1')) : ?>
											<span><?= form_error('sheet1_3_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_2" value="<?= VarDisp($form['sheet1_3_2']) ?>" class="half">
										<?php if (form_error('sheet1_3_2')) : ?>
											<span><?= form_error('sheet1_3_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_3" value="<?= VarDisp($form['sheet1_3_3']) ?>" class="half">
										<?php if (form_error('sheet1_3_3')) : ?>
											<span><?= form_error('sheet1_3_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_4" value="<?= VarDisp($form['sheet1_3_4']) ?>" class="half">
										<?php if (form_error('sheet1_3_4')) : ?>
											<span><?= form_error('sheet1_3_4'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_5" value="<?= VarDisp($form['sheet1_3_5']) ?>" class="half">
										<?php if (form_error('sheet1_3_5')) : ?>
											<span><?= form_error('sheet1_3_5'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_6" value="<?= VarDisp($form['sheet1_3_6']) ?>" class="half">
										<?php if (form_error('sheet1_3_6')) : ?>
											<span><?= form_error('sheet1_3_6'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_3_7" value="<?= VarDisp($form['sheet1_3_7']) ?>" class="half">
										<?php if (form_error('sheet1_3_7')) : ?>
											<span><?= form_error('sheet1_3_7'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_1" value="<?= VarDisp($form['sheet1_4_1']) ?>" class="half">
										<?php if (form_error('sheet1_4_1')) : ?>
											<span><?= form_error('sheet1_4_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_2" value="<?= VarDisp($form['sheet1_4_2']) ?>" class="half">
										<?php if (form_error('sheet1_4_2')) : ?>
											<span><?= form_error('sheet1_4_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_3" value="<?= VarDisp($form['sheet1_4_3']) ?>" class="half">
										<?php if (form_error('sheet1_4_3')) : ?>
											<span><?= form_error('sheet1_4_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_4" value="<?= VarDisp($form['sheet1_4_4']) ?>" class="half">
										<?php if (form_error('sheet1_4_4')) : ?>
											<span><?= form_error('sheet1_4_4'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_5" value="<?= VarDisp($form['sheet1_4_5']) ?>" class="half">
										<?php if (form_error('sheet1_4_5')) : ?>
											<span><?= form_error('sheet1_4_5'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_6" value="<?= VarDisp($form['sheet1_4_6']) ?>" class="half">
										<?php if (form_error('sheet1_4_6')) : ?>
											<span><?= form_error('sheet1_4_6'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_4_7" value="<?= VarDisp($form['sheet1_4_7']) ?>" class="half">
										<?php if (form_error('sheet1_4_7')) : ?>
											<span><?= form_error('sheet1_4_7'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_1" value="<?= VarDisp($form['sheet1_5_1']) ?>" class="half">
										<?php if (form_error('sheet1_5_1')) : ?>
											<span><?= form_error('sheet1_5_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_2" value="<?= VarDisp($form['sheet1_5_2']) ?>" class="half">
										<?php if (form_error('sheet1_5_2')) : ?>
											<span><?= form_error('sheet1_5_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_3" value="<?= VarDisp($form['sheet1_5_3']) ?>" class="half">
										<?php if (form_error('sheet1_5_3')) : ?>
											<span><?= form_error('sheet1_5_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_4" value="<?= VarDisp($form['sheet1_5_4']) ?>" class="half">
										<?php if (form_error('sheet1_5_4')) : ?>
											<span><?= form_error('sheet1_5_4'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_5" value="<?= VarDisp($form['sheet1_5_5']) ?>" class="half">
										<?php if (form_error('sheet1_5_5')) : ?>
											<span><?= form_error('sheet1_5_5'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_6" value="<?= VarDisp($form['sheet1_5_6']) ?>" class="half">
										<?php if (form_error('sheet1_5_6')) : ?>
											<span><?= form_error('sheet1_5_6'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート1</span></th>
									<td>
										<input type="number" name="sheet1_5_7" value="<?= VarDisp($form['sheet1_5_7']) ?>" class="half">
										<?php if (form_error('sheet1_5_7')) : ?>
											<span><?= form_error('sheet1_5_7'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート2</span></th>
									<td>
										<input type="number" name="sheet2_1" value="<?= VarDisp($form['sheet2_1']) ?>" class="half">
										<?php if (form_error('sheet2_1')) : ?>
											<span><?= form_error('sheet2_1'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート2</span></th>
									<td>
										<input type="number" name="sheet2_2" value="<?= VarDisp($form['sheet2_2']) ?>" class="half">
										<?php if (form_error('sheet2_2')) : ?>
											<span><?= form_error('sheet2_2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">シート2</span></th>
									<td>
										<input type="number" name="sheet2_3" value="<?= VarDisp($form['sheet2_3']) ?>" class="half">
										<?php if (form_error('sheet2_3')) : ?>
											<span><?= form_error('sheet2_3'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">表示ステータス</span></th>
									<td>
										<div class="select size_m">
											<?= form_dropdown("status", $select['status'], (isset($form['status']) ? $form['status'] : ""), 'id="status"'); ?>
										</div>
										<?php if (form_error('status')) : ?>
											<span><?= form_error('status'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						</table>

						<a class="btn mb_20 conf_btn">確認</a>
						<a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user" class="btn frame">キャンセル</a>
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

