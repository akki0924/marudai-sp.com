<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $comment ?>管理｜\<\?= $const['site_title_name'] \?\></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="\<\?= SiteDir(); \?\>css/style_admin.css">
<link rel="stylesheet" href="\<\?= SiteDir(); \?\>css/reset.css">
<!--JavaScript-->
<script src="\<\?= SiteDir(); \?\>js/\<\?= JqueryFile() \?\>"></script>
<script type="text/javascript" src="\<\?= SiteDir(); \?\>js/nav.js"></script>
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
			<p class="font_20">\<\?= $const['site_title_name'] \?\></p>
			<div class="pc"><a href="\<\?= SiteDir(); \?\>admin/index/logout" class="btn frame short">ログアウト</a></div>
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
<?php for ($i = 0, $n = count($tableList); $i < $n; $i ++) { ?>
			<li><a href="\<\?= SiteDir(); \?\>admin/<?= $tableList[$i]['targetName'] ?>"><?= $tableList[$i]['comment'] ?>管理</a></li>
<?php } ?>
			<li><a href="\<\?= SiteDir(); \?\>admin/index/logout">ログアウト</a></li>
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
<?php for ($i = 0, $n = count($tableList); $i < $n; $i ++) { ?>
				<div class="col<?= $n ?>"><a href="\<\?= SiteDir(); \?\>admin/<?= $tableList[$i]['targetName'] ?>" class="btn mng<?= ($tableList[$i]['name'] == $tableName ? ' frame' : '') ?>"><?= $tableList[$i]['comment'] ?>管理</a></div>
<?php } ?>
			</div>
		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20">\<\?= $exists ? '<?= $comment ?>情報編集' : '新規<?= $comment ?>登録' \?\></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<form method="post" id="operation_form" name="operation_form" action="\<\?= SiteDir(); \?\>admin/<?= $targetName ?>/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
								<tr>
									<th><span class="required"><?= $tableSel[$i]['comment'] ?></span></th>
<?php if ($tableSel[$i]['name'] == 'status') { ?>
									<td>\<\?= VarDisp($form['status_name']) \?\></td>
<?php } else { ?>
									<td>\<\?= VarDisp($form['<?= $tableSel[$i]['name'] ?>']) \?\></td>
<?php } ?>
								</tr>
<?php } ?>
							</tbody>
						</table>

						<a class="btn mb_20 comp_btn">\<\?= $exists ? '更新' : '登録' \?\></a>
						<a class="btn frame back_btn">戻る</a>
						<input type="hidden" name="id" id="id">
						<input type="hidden" name="action" id="action">
						\<\?php foreach ($form as $key => $val) { \?\>
							\<\?= form_hidden($key, $val); \?\>
						\<\?php } \?\>
					</form>
				</div><!--./bg_gray-->

			</div><!--./max680-->
		</div><!--./container-->
	</section>
</main>

<footer>
	<div class="container">
		<p class="copy">Copyright &copy;\<\?= $const['copyright_name'] \?\> All Rights Reserved</p>
	</div><!--./container-->
</footer>

</body>
</html>

