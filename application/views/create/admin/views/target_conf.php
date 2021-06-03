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
									<td><?= VarDisp($form['l_name']) ?>&nbsp;<?= VarDisp($form['f_name']) ?></td>
								</tr>
								<tr>
									<th><span class="required">ふりがな</span></th>
									<td><?= VarDisp($form['l_name_kana']) ?>&nbsp;<?= VarDisp($form['f_name_kana']) ?></td>
								</tr>
								<tr>
									<th><span class="required">生年月日</span></th>
									<td>
										西暦<?= VarDisp($form['birth_y']) ?>年
										<?= VarDisp($form['birth_m']) ?>月
										<?= VarDisp($form['birth_d']) ?>日
									</td>
								</tr>
								<tr>
									<th><span class="required">電話場号</span></th>
									<td><?= VarDisp($form['tel']) ?></td>
								</tr>
								<tr>
									<th><span class="required">ご住所</span></th>
									<td>
										〒<?= VarDisp($form['zipcode']) ?><br>
										<?= VarDisp($form['pref_name']) ?>
										<?= VarDisp($form['address1']) ?>
										<?= VarDisp($form['address2']) ?>
									</td>
								</tr>
								<tr>
									<th><span class="required">ステータス</span></th>
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
		<p class="copy">Copyright &copy; All Rights Reserved</p>
	</div><!--./container-->
</footer>

</body>
</html>

