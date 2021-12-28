<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>計量記録｜バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<link href="<?= SiteDir(); ?>css/<?= JqueryUiCssFile() ?>" type="text/css" rel="stylesheet">
<!--JavaScript-->
<script type="text/javascript" src="<?= SiteDir(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>js/check_button.js"></script>
<SCRIPT language="JavaScript" src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></SCRIPT>
<SCRIPT language = "JavaScript" src="<?= SiteDir(); ?>js/<?= JqueryUiJsFile() ?>"></SCRIPT>
</head>

<body>
<header class="second">
	<div class="container">
		<p class="font_24 bold txt_center">バーコードシステム｜株式会社マルダイスプリング</p>
	</div>
</header>


<main class="second">
	<div class="container">
		<div class="row just_center mb_10">
			<p class="machine"><?= $title ?></p>
		</div>

		<form action="" class="boad mb_20">
			<div class="row just_start align_end mb_60"></div>
			<div class="row just_start align_end mb_60">
				<p class="bold mb_20 txt_center"><?= $body ?></p>
			</div>

			<a href="<?= SiteDir(); ?><?= $action ?>" class="btn btn_return"><?= $button_str ?></a></center>
		</form>
	</div><!--/.container-->
</main>


</body>
</html>
