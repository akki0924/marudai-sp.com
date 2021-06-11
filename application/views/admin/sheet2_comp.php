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
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet2" class="btn mng frame">チェックシート2管理</a></div>
				<div class="col6"><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/user" class="btn mng">登録者管理</a></div>
			</div>
		</div><!--./container-->
	</section>

	<section id="new_member">
		<div class="container">
			<h2 class="mb_20"><?= $exists ? 'チェックシート2情報編集' : '新規チェックシート2登録' ?></h2>
			<div class="max680">
				<div class="bg_gray pd60">
					<p class="font_20 bold mb_10"><?= $exists ? 'チェックシート2情報更新' : '新規チェックシート2登録' ?>完了</p>
					<p class="mb_40"><?= $exists ? 'チェックシート2情報の更新' : '新規チェックシート2登録' ?>が完了しました。</p>
					<center><a href="<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/sheet2" class="btn short">チェックシート2管理へ戻る</a></center>
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
