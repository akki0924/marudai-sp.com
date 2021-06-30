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
<script type="text/javascript" src="\<\?= SiteDir(); \?\>js/yubinbango.js"></script>
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
			<p class="font_20">\<\?= $const['site_title_name'] \?\></p>
			<div class="pc"><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/index/logout" class="btn frame short">ログアウト</a></div>
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
			<li><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $tableList[$i]['targetName'] ?>"><?= $tableList[$i]['comment'] ?>管理</a></li>
<?php } ?>
			<li><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/index/logout">ログアウト</a></li>
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
				<div class="col<?= $n ?>"><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $tableList[$i]['targetName'] ?>" class="btn mng<?= ($tableList[$i]['name'] == $tableName ? ' frame' : '') ?>"><?= $tableList[$i]['comment'] ?>管理</a></div>
<?php } ?>
			</div>
		</div><!--./container-->
	</section>

	\<\?php if (form_error($const['valid_add_name'])) : \?\>
		<span>\<\?= form_error($const['valid_add_name']); \?\></span><br>
	\<\?php endif; \?\>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20">\<\?= $exists ? '<?= $comment ?>情報編集' : '新規<?= $comment ?>登録' \?\></h2>
			<div class="max680">
				<div class="bg_gray pd60 mb_40">
					<form method="post" id="operation_form" name="operation_form" action="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>/input" class="h-adr">
						<table class="form mb_40">
							<tbody>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
								<tr>
									<th><span class="required"><?= $tableSel[$i]['comment'] ?></span></th>
									<td>
<?php if ($tableSel[$i]['name'] == 'status') { ?>
										<div class="select size_m">
											\<\?= form_dropdown("status", $select['status'], (isset($form['status']) ? $form['status'] : ""), 'id="status"'); \?\>
										</div>
<?php } elseif ($tableSel[$i]['type_simple'] == 'varchar') { ?>
										<input type="text" name="<?= $tableSel[$i]['name'] ?>" value="\<\?= VarDisp($form['<?= $tableSel[$i]['name'] ?>']) \?\>" class="half">
<?php } elseif ($tableSel[$i]['type_simple'] == 'smallint') { ?>
										<input type="number" name="<?= $tableSel[$i]['name'] ?>" value="\<\?= VarDisp($form['<?= $tableSel[$i]['name'] ?>']) \?\>" class="half">
<?php } elseif ($tableSel[$i]['type_simple'] == 'text') { ?>
										<textarea name="<?= $tableSel[$i]['name'] ?>">
											\<\?= VarDisp($form['<?= $tableSel[$i]['name'] ?>']) \?\>
										</textarea>
<?php } ?>
										\<\?php if (form_error('<?= $tableSel[$i]['name'] ?>')) : \?\>
											<span>\<\?= form_error('<?= $tableSel[$i]['name'] ?>'); \?\></span>
										\<\?php endif; \?\>
									</td>
								</tr>
<?php } ?>
							</tbody>
						</table>

						<a class="btn mb_20 conf_btn">確認</a>
						<a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>" class="btn frame">キャンセル</a>
						<input type="hidden" name="id" value="\<\?= (isset($form['id']) ? $form['id'] : '') \?\>">
						<input type="hidden" name="action" id="action">
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

