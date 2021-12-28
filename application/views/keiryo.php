<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>計量記録｜バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/jquery-ui-1.12.1.min.css">
<!--JavaScript-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/check_button.js"></script>
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.12.1.min.js"></script>

<script>
	$(function () {
		$('#member_num,#packing_num').keyup(function () {
			ChangeTotalNum();
		});

		$('.number_key').click(function () {
			$('#input_number').autocomplete({
				source: function (request, response) {
					response(
						$.grep(suggestData, function (value) {
							return value.indexOf(request.term) === 0;
						})
					);
				},
				autoFocus: false,
				delay: 100,
				minLength: 1
			});
			if ($('#input_number').val().length < strLenMax) {
				$('#input_number').val($('#input_number').val() + $(this).find('span').text());
			}
			//$('#search_name').autocomplete("search", $(this).find('span').text())
			$('#input_number').autocomplete("search", $('#input_number').val())
		});
		$('#input_number').autocomplete({
			source: function (request, response) {
				response(
					$.grep(suggestData, function (value) {
						return value.indexOf(request.term) === 0;
					})
				);
			},
			autoFocus: false,
			delay: 100,
			minLength: 1
		});
		$('#submit_btn').click(function () {
			var targetUrl = suggestUrl[$.inArray($('#input_number').val(), suggestData)];
			window.location.href = targetUrl;
		});
	});
	function ChangeTotalNum(){
		var num1 = $('#member_num').val();
		var num2 = $('#packing_num').val();
		var totalNum = num1 * num2;
		$('.total_number').text(totalNum.toLocaleString());

	}
	var suggestData = [];
	var suggestUrl = [];
	for (var i = 0, n = errorData.length; i < n; i++) {
		suggestData[i] = errorData[i][0];
		suggestUrl[i] = errorData[i][1];
	}
	// console.log(suggestData);
	// console.log(suggestUrl);
</script>
</head>

<body>
<header class="second">
	<div class="container">
		<a href="index.html" class="btn_return"><span class="icon_return">初期画面に戻る</span></a>
		<p class="font_24 bold txt_center">計量記録</p>
		<label for="keiryo_trigger" class="btn size_s"><span class="icon_record">計量記録を見る</span></label>
	</div>
</header>


<main class="second">
	<div class="container">
		<div class="row just_center mb_10">
			<p class="machine">養老工場・測量器1</p>
		</div>
		<div class="row just_center max680 mb_20">
			<a href="#" class="btn"><span class="icon_bercode">製品バーコードスキャン</span></a>
		</div>

		<form method="post" id="operation_form" name="operation_form" class="boad mb_20" action="<?= SiteDir(); ?>keiryo">
		<form action="" class="boad mb_20">
			<div class="row just_start align_center mb_20">
				<p class="font_18 blue bold mr_20">品番</p>
				<input type="text" name="code" value="<?= VarDisp($form['code']) ?>" class="txt_center disa half">
			</div>

			<div class="row just_start align_end mb_20">
				<div class="lot mr_30">
					<label for="keyboad_trigger" class="btn second size_m mb_10">ロット</label>
					<span>No.</span><input type="text" name="lot" value="<?= VarDisp($form['lot']) ?>" class="txt_center disa">
				</div>
				<div class="piece mr_30">
					<label for="keyboad_trigger" class="btn second size_m mb_10">員数</label>
					<input type="text" id="member_num" name="member_num" value="<?= VarDisp($form['member_num']) ?>" class="txt_center disa">
				</div>
				<div class="pack mr_60">
					<label for="keyboad_trigger" class="btn second size_m mb_10">荷姿数量</label>
					<input type="text" id="packing_num" name="packing_num" value="<?= VarDisp($form['packing_num']) ?>" class="txt_center disa">
				</div>
				<div class="total">
					<p class="mb_10">数量</p>
					<div class="total_number">0</div>
				</div>
			</div>

			<div class="border mb_20"></div>

			<div class="row align_center mb_40">
				<div class="row just_start">
					<?php foreach ($select['confirm_flg'] as $key => $val) { ?>
					<label class="label_check mr_20">
						<?= form_checkbox("confirm_flg", $key, (is_array($form['confirm_flg']) && $form['confirm_flg'] == $key ? true : false)); ?>
						<span><?= $val ?></span>
					</label>
					<?php } ?>
					<?php foreach ($select['cleaning_flg'] as $key => $val) { ?>
					<label class="label_check">
						<?= form_checkbox("cleaning_flg", $key, (is_array($form['cleaning_flg']) && $form['cleaning_flg'] == $key ? true : false)); ?>
						<span><?= $val ?></span>
					</label>
					<?php } ?>
				</div>
			</div>

			<div class="max360"><label for="comp_trigger" id="btn_record" class="btn size_m">この製品を記録する</label></div>
			<input type="hidden" name="action" value="add">
		</form>
	</div><!--/.container-->


	<!-- キーボード モーダルウィンドウ -->
	<div class="modal_window">
		<input id="keyboad_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="keyboad_trigger" class="modal_close"></label>
			<div class="modal_cont">
				<label for="keyboad_trigger" class="btn_close"></label>
				<form action="">
					<input id="input_number" name="input_number" class="txt_right mb_10 ui-autocomplete-input" autocomplete="off">
					<div class="row">
						<div class="number_key"><span>7</span></div>
						<div class="number_key"><span>8</span></div>
						<div class="number_key"><span>9</span></div>
						<div class="number_key"><span>4</span></div>
						<div class="number_key"><span>5</span></div>
						<div class="number_key"><span>6</span></div>
						<div class="number_key"><span>1</span></div>
						<div class="number_key"><span>2</span></div>
						<div class="number_key"><span>3</span></div>
						<div class="number_key"><span>0</span></div>
						<div class="number_key"><span>-</span></div>
						<input type="button" id="submit_btn" name="submit_btn" value="入力" class="btn_submit">
					</div>
					<input type="reset" class="btn_reset">
				</form>
			</div>
		</div>
	</div><!--/.modal_window-->

	<!-- 作業者 モーダルウィンドウ -->
	<div class="modal_window">
		<input id="comp_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="comp_trigger" class="modal_close"></label>
			<div class="modal_cont">
				<label for="comp_trigger" class="btn_close"></label>
				<form action="">
					<p class="bold mb_20">作業者</p>
					<select name="worker1" class="mb_10">
						<option value="選択1">選択1</option>
						<option value="選択2">選択2</option>
						<option value="選択3">選択3</option>
					</select>
					<select name="worker2" class="mb_40">
						<option value="選択1">選択1</option>
						<option value="選択2">選択2</option>
						<option value="選択3">選択3</option>
					</select>

					<input type="button" value="記録完了" class="btn comp">
				</form>
			</div>
		</div>
	</div><!--/.modal_window-->


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
						<input type="button" value="絞り込む" class="btn second size_s pickup">
					</div>

					<div class="scroll">
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
</main>


</body>
</html>
