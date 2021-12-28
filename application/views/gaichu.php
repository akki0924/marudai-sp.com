<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>外注依頼記録｜バーコードシステム｜株式会社マルダイスプリング</title>
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
		<p class="font_24 bold txt_center">外注依頼記録</p>
		<label for="gaichu_trigger" class="btn size_s"><span class="icon_record">外注依頼記録を見る</span></label>
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
		
		<form action="" class="boad mb_20 gaichu">
			<div class="row just_start align_center mb_20">
				<p class="font_18 blue bold mr_20">品番</p>
				<input type="text" name="number" class="txt_center half">
			</div>

			<div class="row just_start align_end mb_20">
				<div class="lot mr_30">
					<label for="keyboad_trigger" class="btn second size_m mb_10">ロット</label>
					<span>No.</span><input type="text" name="lot" class="txt_center disa">
				</div>
				<div class="piece mr_30">
					<label for="keyboad_trigger" class="btn second size_m mb_10">現場エフ数量</label>
					<input type="text" name="piece" class="txt_center disa">
				</div>
				<div class="pack mr_60">
					<label for="keyboad_trigger" class="btn second size_m mb_10">実荷姿数量</label>
					<input type="text" name="pack" class="txt_center disa">
				</div>
			</div>

			<div class="border mb_20"></div>
			
			<div class="row align_center mb_40">
				<div class="row just_start">
					<label class="label_check mr_20"><input type="checkbox" name="works_conf1" id="works_conf1"><span>状態確認N=3</span></label>
					<label class="label_check"><input type="checkbox" name="works_conf2" id="works_conf2"><span>秤周り清掃確認</span></label>
				</div>
			</div>

			<div class="max360"><label for="comp_trigger" id="btn_record" class="btn size_m">この製品を記録する</label></div>
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
						<select name="start_y" class="data mr_10">
							<option value="2021年">2021年</option>
							<option value="2020年">2020年</option>
							<option value="2019年">2019年</option>
						</select>
						<select name="start_m" class="data mr_10">
							<option value="1月">1月</option>
							<option value="2月">2月</option>
							<option value="3月">3月</option>
							<option value="4月">4月</option>
							<option value="5月">5月</option>
							<option value="6月">6月</option>
							<option value="7月">7月</option>
							<option value="8月">8月</option>
							<option value="9月">9月</option>
							<option value="10月">10月</option>
							<option value="11月">11月</option>
							<option value="12月">12月</option>
						</select>
						<select name="start_d" class="data mr_10">
							<option value="1日">1日</option>
							<option value="2日">2日</option>
							<option value="3日">3日</option>
							<option value="4日">4日</option>
							<option value="5日">5日</option>
							<option value="6日">6日</option>
							<option value="7日">7日</option>
							<option value="8日">8日</option>
							<option value="9日">9日</option>
							<option value="10日">10日</option>
							<option value="11日">11日</option>
							<option value="12日">12日</option>
							<option value="13日">13日</option>
							<option value="14日">14日</option>
							<option value="15日">15日</option>
							<option value="16日">16日</option>
							<option value="17日">17日</option>
							<option value="18日">18日</option>
							<option value="19日">19日</option>
							<option value="20日">20日</option>
							<option value="21日">21日</option>
							<option value="22日">22日</option>
							<option value="23日">23日</option>
							<option value="24日">24日</option>
							<option value="25日">25日</option>
							<option value="26日">26日</option>
							<option value="27日">27日</option>
							<option value="28日">28日</option>
							<option value="29日">29日</option>
							<option value="30日">30日</option>
							<option value="31日">31日</option>
						</select>
						<span class="mr_10">～</span>
						<select name="end_y" class="data mr_10">
							<option value="2021年">2021年</option>
							<option value="2020年">2020年</option>
							<option value="2019年">2019年</option>
						</select>
						<select name="end_m" class="data mr_10">
							<option value="1月">1月</option>
							<option value="2月">2月</option>
							<option value="3月">3月</option>
							<option value="4月">4月</option>
							<option value="5月">5月</option>
							<option value="6月">6月</option>
							<option value="7月">7月</option>
							<option value="8月">8月</option>
							<option value="9月">9月</option>
							<option value="10月">10月</option>
							<option value="11月">11月</option>
							<option value="12月">12月</option>
						</select>
						<select name="end_d" class="data mr_10">
							<option value="1日">1日</option>
							<option value="2日">2日</option>
							<option value="3日">3日</option>
							<option value="4日">4日</option>
							<option value="5日">5日</option>
							<option value="6日">6日</option>
							<option value="7日">7日</option>
							<option value="8日">8日</option>
							<option value="9日">9日</option>
							<option value="10日">10日</option>
							<option value="11日">11日</option>
							<option value="12日">12日</option>
							<option value="13日">13日</option>
							<option value="14日">14日</option>
							<option value="15日">15日</option>
							<option value="16日">16日</option>
							<option value="17日">17日</option>
							<option value="18日">18日</option>
							<option value="19日">19日</option>
							<option value="20日">20日</option>
							<option value="21日">21日</option>
							<option value="22日">22日</option>
							<option value="23日">23日</option>
							<option value="24日">24日</option>
							<option value="25日">25日</option>
							<option value="26日">26日</option>
							<option value="27日">27日</option>
							<option value="28日">28日</option>
							<option value="29日">29日</option>
							<option value="30日">30日</option>
							<option value="31日">31日</option>
						</select>
						<input type="button" value="絞り込む" class="btn second size_s pickup">
					</div>

					<div class="scroll">
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
						  </tbody>
						</table>
					</div><!--/.scroll-->
				</form>
			</div><!--/.modal_cont-->
		</div><!--/.modal_overlay-->
	</div><!--/.modal_window-->
</main>
	

</body>
</html>
