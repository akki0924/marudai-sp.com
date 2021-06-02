<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $exists ? '編集' : '新規利用者登録' ?>｜利用者管理｜<?= $const['site_title_name'] ?></title>
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
			<li><a href="<?= SiteDir(); ?>admin/reserve">応募管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/user">利用者管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/seat">座席管理</a></li>
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
				<div class="col3"><a href="<?= SiteDir(); ?>admin/reserve" class="btn mng">応募管理</a></div>
				<div class="col3"><a href="<?= SiteDir(); ?>admin/user" class="btn frame mng">利用者管理</a></div>
				<div class="col3"><a href="<?= SiteDir(); ?>admin/seat" class="btn mng">座席管理</a></div>
			</div>

		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20"><?= $exists ? '利用者情報編集' : '新規利用者登録' ?></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<?php if ($exists) { ?>
						<p class="font_20 bold mb_40">利用者ID：<?= VarDisp($form['id']) ?></p>
					<?php } ?>
					<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin/user/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
								<tr>
									<th><span class="required">お名前</span></th>
									<td>
									<div class="row just_start align_center">
										<input type="text" name="l_name" value="<?= VarDisp($form['l_name']) ?>" class="name mr_10" placeholder="性">
										<input type="text" name="f_name" value="<?= VarDisp($form['f_name']) ?>" class="name" placeholder="名">
									</div><!--/.row-->
									<?php if (form_error('l_name')) : ?>
										<span id="errors_l_name"><?= form_error('l_name'); ?></span><br>
									<?php endif; ?>
									<?php if (form_error('f_name')) : ?>
										<span id="errors_f_name"><?= form_error('f_name'); ?></span>
									<?php endif; ?>
								</td>
								</tr>
								<tr>
									<th><span class="required">ふりがな</span></th>
									<td>
										<div class="row just_start align_center">
											<input type="text" name="l_name_kana"  value="<?= VarDisp($form['l_name_kana']) ?>" class="name mr_10" placeholder="セイ">
											<input type="text" name="f_name_kana"  value="<?= VarDisp($form['f_name_kana']) ?>" class="name" placeholder="メイ">
										</div><!--/.row-->
										<?php if (form_error('l_name_kana')) : ?>
											<span id="errors_l_name_kana"><?= form_error('l_name_kana'); ?></span><br>
										<?php endif; ?>
										<?php if (form_error('f_name_kana')) : ?>
											<span id="errors_f_name_kana"><?= form_error('f_name_kana'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">生年月日</span></th>
									<td>
										<div class="row just_start align_center">
										<p class="mr_10">西暦</p>
											<input type="tel" name="birth_y" value="<?= VarDisp($form['birth_y']) ?>" class="birth mr_10" maxlength="4">
											<p class="mr_10">年</p>
											<input type="tel" name="birth_m" value="<?= VarDisp($form['birth_m']) ?>" class="birth mr_10" maxlength="2">
											<p class="mr_10">月</p>
											<input type="tel" name="birth_d" value="<?= VarDisp($form['birth_d']) ?>" class="birth mr_10" maxlength="2">
											<p>日</p>
											<?php if (form_error('birth_y')) : ?>
												<span><?= form_error('birth_y'); ?></span>
											<?php endif; ?>
										</div><!--/.row-->
									</td>
								</tr>
								<tr>
									<th><span class="required">電話場号</span></th>
									<td>
										<input type="tel" name="tel" value="<?= VarDisp($form['tel']) ?>" class="half">
										<?php if (form_error('tel')) : ?>
											<span><?= form_error('tel'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">ご住所</span></th>
									<td>
										<div class="row just_start align_center mb_10">
											<span class="p-country-name" style="display:none;">Japan</span>
											<p class="mr_10">〒</p>
											<input type="tel" name="zipcode" value="<?= VarDisp($form['zipcode']) ?>" class="post p-postal-code mr_10" maxlength="7">
											<?php if (form_error('zipcode')) : ?>
												<span><?= form_error('zipcode'); ?></span>
											<?php endif; ?>
										</div><!--/.row-->

										<?= form_dropdown("pref_id", $select['pref'], (isset($form['pref_id']) ? $form['pref_id'] : ""), 'class="p-region-id"'); ?>
										<?php if (form_error('pref_id')) : ?>
											<span><?= form_error('pref_id'); ?></span>
										<?php endif; ?>
										<input type="text" name="address1" value="<?= VarDisp($form['address1']) ?>" placeholder="市区町村名" class="p-locality p-street-address p-extended-address">
										<?php if (form_error('address1')) : ?>
											<span><?= form_error('address1'); ?></span>
										<?php endif; ?>
										<div class="mb_10"></div>

										<input type="text" name="address2" value="<?= VarDisp($form['address2']) ?>" placeholder="ビル名">
										<?php if (form_error('address2')) : ?>
											<span><?= form_error('address2'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">ステータス</span></th>
									<td>
										<div class="select size_m">
											<?= form_dropdown("status", $select['status'], (isset($form['status']) ? $form['status'] : ""), 'id="status"'); ?>
										</div>
										<?php if (form_error('status')) : ?>
											<br><span><?= form_error('status'); ?></span>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						</table>

						<a class="btn mb_20 conf_btn">確認</a>
						<a href="<?= SiteDir(); ?>admin/user" class="btn frame">キャンセル</a>
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
		<p class="copy">Copyright &copy;az-rentacar.net All Rights Reserved</p>
	</div><!--./container-->
</footer>

</body>
</html>

