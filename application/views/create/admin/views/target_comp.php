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

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20">\<\?= $exists ? '<?= $comment ?>情報編集' : '新規<?= $comment ?>登録' \?\></h2>
			<div class="max680">
				<div class="bg_gray pd60">
					<p class="font_20 bold mb_10">\<\?= $exists ? '<?= $comment ?>情報更新' : '新規<?= $comment ?>登録' \?\>完了</p>
					<p class="mb_40">\<\?= $exists ? '<?= $comment ?>情報の更新' : '新規<?= $comment ?>登録' \?\>が完了しました。</p>
					<center><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>" class="btn short"><?= $comment ?>管理へ戻る</a></center>
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
