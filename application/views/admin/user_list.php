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
<script language="JavaScript">
$(function() {
	$('.edit_btn').click(function() {
		$('#id').val( $(this).data('id') );

		$('#operation_form').attr( 'action', '<?= SiteDir(); ?>admin/user/input' );
		$('#operation_form').submit();
	});
});
</script>

</head>

<body>
<header>
	<div class="container">
		<div class="row align_center">
			<div class="logo"><?= $const['site_title_name'] ?></div>
			<div class="member"><a href="<?= SiteDir(); ?>admin/index/logout" class="btn frame shortest">ログアウト</a></div>
		</div>
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
			<li><a href="<?= SiteDir(); ?>admin/sheet1">チェックシート1管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/sheet2">チェックシート2管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/user">登録者管理</a></li>
			<li><a href="<?= SiteDir(); ?>admin/index/logout">ログアウト</a></li>
		</ul>
	</nav>
	<!-- /sp menu ---------------------------->
</header>

<main>
	<div class="bg_gray pd20 border"><h1>管理者画面</h1></div>

	<section id="management">
		<div class="container">
			<div class="row mb_80">
				<div class="col4"><a href="<?= SiteDir(); ?>admin/admin" class="btn mng">ログイン管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/sheet1" class="btn mng">チェックシート1管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/sheet2" class="btn mng">チェックシート2管理</a></div>
				<div class="col4"><a href="<?= SiteDir(); ?>admin/user" class="btn mng frame">登録者管理</a></div>
			</div>
		</div><!--/.container-->

		<div class="container">
		<form method="post" id="operation_form" name="operation_form" action="<?= SiteDir(); ?>admin/user">
			<h2 class="mb_40">登録者管理</h2>
			<div class="scroll">
				<table class="management">
					<tbody>
					<?php if (isset($list) && count($list) > 0) { ?>
						<tr>
							<th>ユーザーID</th>
							<th>登録日</th>
							<th>名前</th>
							<th>来場日（座席）</th>
							<th>ステータス</th>
							<th>&nbsp;</th>
						</tr>
						<?php for ($i = 0, $n = count($list); $i < $n; $i ++) { ?>
						<tr>
							<td><?= $list[$i]['id'] ?></td>
							<td><?= $list[$i]['regist_date'] ?></td>
							<td><?= $list[$i]['name'] ?></td>
							<td>
								<?php for ($date_i = 0, $date_n = count($list[$i]['date_list']); $date_i < $date_n; $date_i ++) { ?>
									<?= (isset($list[$i]['date_list'][$date_i]) ? $list[$i]['date_list'][$date_i] : '') ?>
									<?= (isset($list[$i]['seat_id_list'][$date_i]) ? '（' . $list[$i]['seat_id_list'][$date_i] . '）' : '') ?>
									<br>
								<?php } ?>
							</td>
							<td><?= $list[$i]['status_name'] ?></td>
							<td><a class="btn frame shortest edit_btn" data-id="<?= $list[$i]['id'] ?>">編集</a></td>
						</tr>
						<?php } ?>
					<?php } else { ?>
						<tr><td bgcolor="#F2F2F2" align="center" valign="middle">no list</td></tr>
					<?php } ?>
					</tbody>
				</table>
			</div><!--./scroll-->
			<input type="hidden" name="id" id="id">
			<input type="hidden" name="action" id="action">
		</form>
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
