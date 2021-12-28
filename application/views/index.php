<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
</head>

<body>
<header>
	<div class="container">
		<div class="logo mb_100"><img src="<?= SiteDir(); ?>img/logo.gif" alt="ロゴ"></div>
	</div>
</header>


<main>
	<div class="container">
		<p class="txt_center bold mb_10">測量器のバーコードをスキャンして記録を始めてください</p>
		<div class="row just_center max680 mb_60">
			<a href="<?= SiteDir(); ?>keiryo" class="btn"><span class="icon_bercode">測量器バーコードスキャン</span></a>
		</div>
		<div class="border mb_20"></div>
		<div class="max360">
			<label for="keiryo_trigger" class="btn second size_m mb_20"><span class="icon_record">計量記録を見る</span></label>
			<label for="gaichu_trigger" class="btn second size_m mb_40"><span class="icon_record">外注依頼記録を見る</span></label>
			<a href="<?= SiteDir(); ?>worker" class="btn third size_m"><span class="icon_worker">作成者を追加する</span></a>
		</div>
	</div>


	<!-- 計量記録 モーダルウィンドウ ------------------------------------------------>
	<div class="modal_window">
		<input id="keiryo_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="keiryo_trigger" class="modal_close"></label>
			<div class="modal_cont record">
				<label for="keiryo_trigger" class="btn_close"></label>
				<form action="">
					<p class="font_18 navy bold mb_20">計量記録</p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y", $select['year'], (isset($form['start_y']) ? $form['start_y'] : ""), 'id="start_y" class="data mr_10"'); ?>
						<?= form_dropdown("start_m", $select['month'], (isset($form['start_m']) ? $form['start_m'] : ""), 'id="start_m" class="data mr_10"'); ?>
						<?= form_dropdown("start_d", $select['day'], (isset($form['start_d']) ? $form['start_d'] : ""), 'id="start_d" class="data mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y", $select['year'], (isset($form['end_y']) ? $form['end_y'] : ""), 'id="end_y" class="data mr_10"'); ?>
						<?= form_dropdown("end_m", $select['month'], (isset($form['end_m']) ? $form['end_m'] : ""), 'id="end_m" class="data mr_10"'); ?>
						<?= form_dropdown("end_d", $select['day'], (isset($form['end_d']) ? $form['end_d'] : ""), 'id="end_d" class="data mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn second size_ss pickup">
					</div>

					<div class="scroll measurement_list">
						<?php if (isset($list) && count($list) > 0) { ?>
						<table class="record">
						  <tbody>
						    <tr>
						      <th>作業日</th>
						      <th>時間</th>
						      <th>場所・秤</th>
						      <th>品番</th>
						      <th>ロット</th>
						      <th>員数</th>
						      <th>荷姿数量</th>
						      <th>数量</th>
						      <th>作業者</th>
						    </tr>
							<?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
						    <tr>
						      <td>11/1</td>
						      <td>2:00</td>
						      <td>養老工場・測量器1</td>
						      <td>00000-00000</td>
						      <td>000000-0</td>
						      <td>1000</td>
						      <td>100</td>
						      <td>100000</td>
						      <td>山田・田中</td>
						    </tr>
							<?php } ?>
						  </tbody>
						</table>
						<?php } else { ?>
							<div>no list</div>
						<?php } ?>
					</div><!--/.scroll-->
				</form>
			</div><!--/.modal_cont-->
		</div><!--/.modal_overlay-->
	</div><!--/.modal_window-->


	<!-- 外注依頼記録 モーダルウィンドウ ------------------------------------------------>
	<div class="modal_window">
		<input id="gaichu_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="gaichu_trigger" class="modal_close"></label>
			<div class="modal_cont record">
				<label for="gaichu_trigger" class="btn_close"></label>
				<form action="">
					<p class="font_18 navy bold mb_20">外注依頼記録</p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y", $select['year'], (isset($form['start_y']) ? $form['start_y'] : ""), 'id="start_y" class="data mr_10"'); ?>
						<?= form_dropdown("start_m", $select['month'], (isset($form['start_m']) ? $form['start_m'] : ""), 'id="start_m" class="data mr_10"'); ?>
						<?= form_dropdown("start_d", $select['day'], (isset($form['start_d']) ? $form['start_d'] : ""), 'id="start_d" class="data mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y", $select['year'], (isset($form['end_y']) ? $form['end_y'] : ""), 'id="end_y" class="data mr_10"'); ?>
						<?= form_dropdown("end_m", $select['month'], (isset($form['end_m']) ? $form['end_m'] : ""), 'id="end_m" class="data mr_10"'); ?>
						<?= form_dropdown("end_d", $select['day'], (isset($form['end_d']) ? $form['end_d'] : ""), 'id="end_d" class="data mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn second size_ss pickup">
					</div>

					<div class="scroll outsourcing_list">
						<?php if (isset($list) && count($list) > 0) { ?>
						<table class="record">
						  <tbody>
						    <tr>
						      <th>作業日</th>
						      <th>時間</th>
						      <th>場所・秤</th>
						      <th>品番</th>
						      <th>ロット</th>
						      <th>現場エフ数量</th>
						      <th>実荷姿数量</th>
						      <th>作業者</th>
						    </tr>
							<?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
						    <tr>
						      <td>11/1</td>
						      <td>2:00</td>
						      <td>養老工場・測量器1</td>
						      <td>00000-00000</td>
						      <td>000000-0</td>
						      <td>10000</td>
						      <td>10000</td>
						      <td>山田・田中</td>
						    </tr>
							<?php } ?>
						  </tbody>
						</table>
						<?php } else { ?>
							<div>no list</div>
						<?php } ?>
					</div><!--/.scroll-->
				</form>
			</div><!--/.modal_cont-->
		</div><!--/.modal_overlay-->
	</div><!--/.modal_window-->
</main>


</body>
</html>
