<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>作業者追加｜バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<link rel="stylesheet" type="text/css" href="<?= SiteDir(); ?>form/css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script>
// 読込み完了時
$(function() {
$('.add_btn').click(function() {
      $('#action').val('add');
      $('#operation_form').attr( 'action', '<?= SiteDir(); ?>worker' );
      $('#operation_form').submit();
   });
});
</script>
</head>

<body>
<header class="second">
	<div class="container">
		<a href="<?= SiteDir(); ?>" class="btn_return"><span class="icon_return">初期画面に戻る</span></a>
		<p class="font_24 bold txt_center">作業者追加</p>
	</div>
</header>


<main class="second">
	<div class="container">
		<div class="max680">
			<form method="post" id="operation_form" action="<?= SiteDir(); ?>worker" class="row align_center boad mb_20">
				<div class="row align_center">
					<p class="font_14 mr_10">姓</p>
					<input type="text" name="name_l" value="<?= VarDisp($form['name_l']) ?>" class="short mr_10">
					<p class="font_14 mr_10">名</p>
					<input type="text" name="name_f" value="<?= VarDisp($form['name_f']) ?>" class="short mr_10">
				</div>
				<input type="button" value="追加" class="btn add_btn size_s">
				<input type="hidden" id="action" name="action">
			</form>
			<?php if (form_error('name_l')) : ?>
				<br><span><?= form_error('name_l'); ?></span>
			<?php endif; ?>
			<?php if (form_error('name_f')) : ?>
				<br><span><?= form_error('name_f'); ?></span>
			<?php endif; ?>
			<div class="border mb_20"></div>

			<div class="scroll">
				<table class="mng worker">
				  <tbody>
				  <?php if (isset($list) && count($list) > 0) { ?>
				    <tr>
				      <th>No.</th>
				      <th>作業者名</th>
				    </tr>
					<?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
				    <tr>
				      <td><?= $no ?></td>
				      <td><?= $list[$i]['name'] ?></td>
				    </tr>
					<?php } ?>
					<?php } else { ?>
						<tr><td bgcolor="#F2F2F2" align="center" valign="middle">no list</td></tr>
					<?php } ?>
				  </tbody>
				</table>
			</div>
		</div><!--/.max680-->
	</div><!--/.container-->
</main>


</body>
</html>
