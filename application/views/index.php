<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>バーコードシステム｜株式会社マルダイスプリング</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link href="<?= SiteDir(); ?>css/<?= JqueryUiCssFile() ?>" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/style.css">
<link rel="stylesheet" href="<?= SiteDir(); ?>css/reset.css">
<link rel="stylesheet" type="text/css" href="<?= SiteDir(); ?>form/css">
<!--JavaScript-->
<script src="<?= SiteDir(); ?>js/<?= JqueryFile() ?>"></script>
<script src="<?= SiteDir(); ?>js/<?= JqueryUiJsFile() ?>"></script>
<script type="text/javascript" src="<?= SiteDir(); ?>form/js"></script>
<script language="JavaScript">
var scanFlg = false;
$(function() {
	$('.barcode_btn').click(function() {
		scanFlg = true;
		// フォーカスする
		$('#inputCode').focus();
		LayerStart();
	});
	$(document).on('keypress', '#inputCode', function(e) {
		if (scanFlg) {
			console.log('action');
			// 改行処理
			if (e.charCode == 13) {
				var codes  = $('#inputCode').val();
				window.location.href = '<?= SiteDir(); ?>keiryo/input/' + codes;
			}
		}
	});
	//テキストボックスのフォーカスが外れたら発動
	$('#inputCode').blur(function() {
		LayerEnd();
		scanFlg = false;
	});
	/**
	 * 計量記録 検索ボタン
	 */
	$('.search1_btn').click(function () {
		var ajaxUrl = 'index/ajax_list';
		var ajaxObj = {
			start_y : $('#start_y1').val(),
			start_m : $('#start_m1').val(),
			start_d : $('#start_d1').val(),
			end_y : $('#end_y1').val(),
			end_m : $('#end_m1').val(),
			end_d : $('#end_d1').val(),
			type : 1,
		};
		AjaxAction(ajaxUrl, ajaxObj);
	});
	/**
	 * 外注依頼記録 検索ボタン
	 */
	$('.search2_btn').click(function () {
		var ajaxUrl = 'index/ajax_list';
		var ajaxObj = {
			start_y : $('#start_y2').val(),
			start_m : $('#start_m2').val(),
			start_d : $('#start_d2').val(),
			end_y : $('#end_y2').val(),
			end_m : $('#end_m2').val(),
			end_d : $('#end_d2').val(),
			type : 2,
		};
		AjaxAction(ajaxUrl, ajaxObj);
	});
	/**
	 * 防錆記録 検索ボタン
	 */
	$('.search3_btn').click(function () {
		var ajaxUrl = 'index/ajax_list';
		var ajaxObj = {
			start_y : $('#start_y3').val(),
			start_m : $('#start_m3').val(),
			start_d : $('#start_d3').val(),
			end_y : $('#end_y3').val(),
			end_m : $('#end_m3').val(),
			end_d : $('#end_d3').val(),
			type : 3,
		};
		AjaxAction(ajaxUrl, ajaxObj);
	});
    $(document).on('click', '.pdf_link', function() {
        var ajaxUrl = 'keiryo/ajax_pdf';
        var ajaxObj = {
            number : $(this).text(),
            type : '',
        };
        AjaxAction(ajaxUrl, ajaxObj, PdfLinkAction);
    });
	/**
     * 印刷ボタン
     */
    $(document).on('click', '.print_btn', function() {
		window.print();
    });
});
	/**
     * PDF表示処理
     */
	function PdfLinkAction(){
    var inputCode = $('#inputCode').val();
    if (inputCode) {
        window.open(inputCode);
    }
}
</script>


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
			<a class="btn barcode_btn"><span class="icon_bercode">測量器バーコードスキャン</span></a>
		</div>

		<input type="text" id="inputCode" name="inputCode" value="" data-role:"none">

		<div class="border mb_20"></div>
		<div class="max360">
			<label for="keiryo_trigger" class="btn second size_m mb_20"><span class="icon_record">計量記録を見る</span></label>
			<label for="gaichu_trigger" class="btn second size_m mb_20"><span class="icon_record">外注依頼記録を見る</span></label>
			<label for="bousei_trigger" class="btn second size_m mb_40"><span class="icon_record">防錆記録を見る</span></label>
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
					<p class="font_18 navy bold mb_20">計量記録　<input type="button" value="印刷" class="btn print_btn second size_ss pickup no_print"></p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y1", $select['year'], (isset($form['start_y1']) ? $form['start_y1'] : ""), 'id="start_y1" class="data mr_10"'); ?>
						<?= form_dropdown("start_m1", $select['month'], (isset($form['start_m1']) ? $form['start_m1'] : ""), 'id="start_m1" class="data mr_10"'); ?>
						<?= form_dropdown("start_d1", $select['day'], (isset($form['start_d1']) ? $form['start_d1'] : ""), 'id="start_d1" class="data mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y1", $select['year'], (isset($form['end_y1']) ? $form['end_y1'] : ""), 'id="end_y1" class="data mr_10"'); ?>
						<?= form_dropdown("end_m1", $select['month'], (isset($form['end_m1']) ? $form['end_m1'] : ""), 'id="end_m1" class="data mr_10"'); ?>
						<?= form_dropdown("end_d1", $select['day'], (isset($form['end_d1']) ? $form['end_d1'] : ""), 'id="end_d1" class="data mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn search1_btn second size_ss pickup no_print">
					</div>

					<div id="search_m_list" class="scroll measurement_list">
						<?php if (isset($list_m) && count($list_m) > 0) { ?>
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
							<?php for ($i = 0, $no = 1, $n = count($list_m); $i < $n; $i ++, $no ++) { ?>
						    <tr>
								<td><?= $list_m[$i]['start_date'] ?></td>
								<td><?= $list_m[$i]['start_time'] ?></td>
								<td><?= $list_m[$i]['place_name'] ?>・<?= $list_m[$i]['place_scale'] ?></td>
								<?php if ($list_m[$i]['pdf_exists']) { ?>
									<td><a class="pdf_link"><?= $list_m[$i]['number'] ?></a></td>
								<?php } else { ?>
									<td><?= $list_m[$i]['number'] ?></td>
								<?php } ?>
								<td><?= $list_m[$i]['lot'] ?></td>
								<td><?= $list_m[$i]['num'] ?></td>
								<td><?= $list_m[$i]['packing'] ?></td>
								<td><?= $list_m[$i]['total_num'] ?></td>
								<td>
									<?= $list_m[$i]['worker1_name'] ?>
									<?= ($list_m[$i]['worker2_name'] ? '・' . $list_m[$i]['worker2_name'] : '') ?>
								</td>
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
					<p class="font_18 navy bold mb_20">外注依頼記録　<input type="button" value="印刷" class="btn print_btn second size_ss pickup no_print"></p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y2", $select['year'], (isset($form['start_y2']) ? $form['start_y2'] : ""), 'id="start_y2" class="data mr_10"'); ?>
						<?= form_dropdown("start_m2", $select['month'], (isset($form['start_m2']) ? $form['start_m2'] : ""), 'id="start_m2" class="data mr_10"'); ?>
						<?= form_dropdown("start_d2", $select['day'], (isset($form['start_d2']) ? $form['start_d2'] : ""), 'id="start_d2" class="data mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y2", $select['year'], (isset($form['end_y2']) ? $form['end_y2'] : ""), 'id="end_y2" class="data mr_10"'); ?>
						<?= form_dropdown("end_m2", $select['month'], (isset($form['end_m2']) ? $form['end_m2'] : ""), 'id="end_m2" class="data mr_10"'); ?>
						<?= form_dropdown("end_d2", $select['day'], (isset($form['end_d2']) ? $form['end_d2'] : ""), 'id="end_d2" class="data mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn search2_btn second size_ss pickup no_print">
					</div>

					<div id="search_o_list" class="scroll outsourcing_list">
						<?php if (isset($list_o) && count($list_o) > 0) { ?>
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
								<th>継続フラグ</th>
								<th>作業者</th>
						    </tr>
							<?php for ($i = 0, $no = 1, $n = count($list_o); $i < $n; $i ++, $no ++) { ?>
						    <tr>
								<td><?= $list_o[$i]['start_date'] ?></td>
								<td><?= $list_o[$i]['start_time'] ?></td>
								<td><?= $list_o[$i]['place_name'] ?>・<?= $list_o[$i]['place_scale'] ?></td>
								<?php if ($list_o[$i]['pdf_exists']) { ?>
									<td><a class="pdf_link"><?= $list_o[$i]['number'] ?></a></td>
								<?php } else { ?>
									<td><?= $list_o[$i]['number'] ?></td>
								<?php } ?>
								<td><?= $list_o[$i]['lot'] ?></td>
								<td><?= $list_o[$i]['num'] ?></td>
								<td><?= $list_o[$i]['packing'] ?></td>
								<td><?= $list_o[$i]['continue_flg_name'] ?></td>
								<td>
									<?= $list_o[$i]['worker1_name'] ?>
									<?= ($list_o[$i]['worker2_name'] ? '・' . $list_o[$i]['worker2_name'] : '') ?>
								</td>
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


	<!-- 防錆記録 モーダルウィンドウ ------------------------------------------------>
	<div class="modal_window">
		<input id="bousei_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="bousei_trigger" class="modal_close"></label>
			<div class="modal_cont record">
				<label for="bousei_trigger" class="btn_close"></label>
				<form action="">
					<p class="font_18 navy bold mb_20">防錆記録　<input type="button" value="印刷" class="btn print_btn second size_ss pickup no_print"></p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y3", $select['year'], (isset($form['start_y3']) ? $form['start_y3'] : ""), 'id="start_y3" class="data mr_10"'); ?>
						<?= form_dropdown("start_m3", $select['month'], (isset($form['start_m3']) ? $form['start_m3'] : ""), 'id="start_m3" class="data mr_10"'); ?>
						<?= form_dropdown("start_d3", $select['day'], (isset($form['start_d3']) ? $form['start_d3'] : ""), 'id="start_d3" class="data mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y3", $select['year'], (isset($form['end_y3']) ? $form['end_y3'] : ""), 'id="end_y3" class="data mr_10"'); ?>
						<?= form_dropdown("end_m3", $select['month'], (isset($form['end_m3']) ? $form['end_m3'] : ""), 'id="end_m3" class="data mr_10"'); ?>
						<?= form_dropdown("end_d3", $select['day'], (isset($form['end_d3']) ? $form['end_d3'] : ""), 'id="end_d3" class="data mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn search3_btn second size_ss pickup no_print">
					</div>

					<div id="search_b_list" class="scroll outsourcing_list">
						<?php if (isset($list_b) && count($list_b) > 0) { ?>
						<table class="record">
						  <tbody>
							<tr>
								<th>作業日</th>
								<th>時間</th>
								<th>場所・秤</th>
								<th>品番</th>
								<th>ロット</th>
								<th>数量</th>
								<th>防錆清掃チェック</th>
								<th>カゴの異物チェック</th>
								<th>継続フラグ</th>
								<th>作業者</th>
							</tr>
							<?php for ($i = 0, $no = 1, $n = count($list_b); $i < $n; $i ++, $no ++) { ?>
						    <tr>
								<td><?= $list_b[$i]['start_date'] ?></td>
								<td><?= $list_b[$i]['start_time'] ?></td>
								<td><?= $list_b[$i]['place_name'] ?>・<?= $list_b[$i]['place_scale'] ?></td>
								<?php if ($list_b[$i]['pdf_exists']) { ?>
									<td><a class="pdf_link"><?= $list_b[$i]['number'] ?></a></td>
								<?php } else { ?>
									<td><?= $list_b[$i]['number'] ?></td>
								<?php } ?>
								<td><?= $list_b[$i]['lot'] ?></td>
								<td><?= $list_b[$i]['num'] ?></td>
								<td><?= $list_b[$i]['bousei_cleaning_flg_name'] ?></td>
								<td><?= $list_b[$i]['trash_flg_name'] ?></td>
								<td><?= $list_b[$i]['continue_flg_name'] ?></td>
								<td>
									<?= $list_b[$i]['worker1_name'] ?>
									<?= ($list_b[$i]['worker2_name'] ? '・' . $list_b[$i]['worker2_name'] : '') ?>
								</td>
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
