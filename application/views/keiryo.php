<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>
	<?php
        if ($placeData['type'] == 1) {
            print '計量記録';
        } elseif ($placeData['type'] == 2) {
            print '外注依頼記録';
        } elseif ($placeData['type'] == 3) {
            print '防錆記録';
        }
    ?>｜バーコードシステム｜株式会社マルダイスプリング
</title>
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
<script type="text/javascript" src="<?= SiteDir(); ?>js/check_button.js"></script>

<script type="text/javascript" src="<?= SiteDir(); ?>keiryo/js/<?= $placeData['code'] ?>"></script>
</head>

<body>
<header class="second no_print">
	<div class="container no_print">
		<a href="index.html" class="btn_return"><span class="icon_return">初期画面に戻る</span></a>
		<p class="font_24 bold txt_center">
			<?php
                if ($placeData['type'] == 1) {
                    print '計量記録';
                } elseif ($placeData['type'] == 2) {
                    print '外注依頼記録';
                } elseif ($placeData['type'] == 3) {
                    print '防錆記録';
                }
            ?>
		</p>
		<label for="keiryo_trigger" class="btn size_s">
			<span class="icon_record">
				<?php
                    if ($placeData['type'] == 1) {
                        print '計量記録';
                    } elseif ($placeData['type'] == 2) {
                        print '外注依頼記録';
                    } elseif ($placeData['type'] == 3) {
                        print '防錆記録';
                    }
                ?>を見る
		</span>
		</label>
	</div>
</header>


<main class="second no_print">
	<div class="container">
		<div class="row just_center mb_10">
			<p class="machine">
				<?= ($placeData['place_name'] ? $placeData['place_name'] : '') ?>・
				<?= ($placeData['scale'] ? $placeData['scale'] : '') ?>
			</p>
		</div>
		<div class="row just_center max680 mb_20">
			<a class="btn barcode_btn"><span class="icon_bercode">製品バーコードスキャン</span></a>
			<input type="text" id="inputCode" name="inputCode" value="" data-role:"none">
		</div>

		<form method="post" id="operation_form" name="operation_form" class="boad mb_20 no_print<?= $placeData['type'] == 2 ? ' gaichu' : '' ?>" action="<?= SiteDir(); ?>keiryo/input/<?= $placeData['code'] ?>">
			<div id="input_group1"  class="row just_start align_center mb_20">
				<p class="font_18 blue bold mr_20">品番</p>
				<input type="text" id="number" name="number" value="<?= VarDisp($form['number']) ?>" class="txt_center disa half">
				<label id="btn_pdf" class="btn pdf_btn size_m max120">PDF</label>
			</div>

			<div id="input_group2" class="row just_start align_end mb_20">
				<div class="lot mr_30">
					<label for="keyboad_trigger" class="btn lot_btn second size_m mb_10">ロット</label>
					<span>No.</span><input type="text" id="lot" name="lot" value="<?= VarDisp($form['lot']) ?>" class="txt_center disa">
				</div>
				<?php if ($placeData['type'] == 1) { ?>
				<div class="piece mr_30">
					<label for="keyboad_trigger" class="btn num_btn second size_m mb_10">員数</label>
					<input type="tel" id="member_num" name="member_num" value="<?= VarDisp($form['member_num']) ?>" class="txt_center disa">
				</div>
				<div class="pack mr_60">
					<label for="keyboad_trigger" class="btn pack_btn second size_m mb_10">荷姿数量</label>
					<input type="tel" id="packing_num" name="packing_num" value="<?= VarDisp($form['packing_num']) ?>" class="txt_center disa">
				</div>
				<div class="total">
					<p class="mb_10">数量</p>
					<div class="total_number">0</div>
				</div>
				<?php } elseif ($placeData['type'] == 2) { ?>
				<div class="piece mr_30">
					<label for="keyboad_trigger" class="btn num_btn second size_m mb_10">現場エフ数量</label>
					<input type="text" id="f_num" name="f_num" value="<?= VarDisp($form['f_num']) ?>" class="txt_center disa">
				</div>
				<div class="pack mr_30">
					<label for="keyboad_trigger" class="btn pack_btn second size_m mb_10">実荷姿数量</label>
					<input type="text" id="packing_num_total" name="packing_num_total" value="<?= VarDisp($form['packing_num_total']) ?>" class="txt_center disa">
				</div>
				<div class="other">
					<p class="mb_10">継続確認</p>
					<?= form_dropdown("continue_flg", $select['continue_flg'], (isset($form['continue_flg']) ? $form['continue_flg'] : ""), 'id="continue_flg"'); ?>
				</div>
				<?php } elseif ($placeData['type'] == 3) { ?>
				<div class="mr_10">
					<label for="keyboad_trigger" class="btn num_btn second size_m mb_10">数量</label>
					<input type="text" id="bousei_num" name="bousei_num" value="<?= VarDisp($form['bousei_num']) ?>" class="txt_center disa">
				</div>
				<div class="other">
					<p class="mb_10"><!--継続確認--></p>
					<?= form_dropdown("continue_flg", $select['continue_flg'], (isset($form['continue_flg']) ? $form['continue_flg'] : ""), 'id="continue_flg"'); ?>
				</div>
				<?php } ?>
			</div>

			<div class="border mb_20"></div>

			<div class="row align_center mb_40">
				<div class="row just_start">
					<?php if ($placeData['type'] != 3) { ?>
						<?php foreach ($select['confirm_flg'] as $key => $val) { ?>
						<label class="label_check mr_20">
							<?= form_checkbox("confirm_flg", $key, (is_array($form['confirm_flg']) && $form['confirm_flg'] == $key ? true : false), 'id="confirm_flg"'); ?>
							<span><?= $val ?></span>
						</label>
						<?php } ?>
						<?php foreach ($select['cleaning_flg'] as $key => $val) { ?>
						<label class="label_check">
							<?= form_checkbox("cleaning_flg", $key, (is_array($form['cleaning_flg']) && $form['cleaning_flg'] == $key ? true : false), 'id="cleaning_flg"'); ?>
							<span><?= $val ?></span>
						</label>
						<?php } ?>
					<?php } else { ?>
						<?php foreach ($select['bousei_cleaning_flg'] as $key => $val) { ?>
						<label class="label_check mr_20">
							<?= form_checkbox("confirm_flg", $key, (is_array($form['confirm_flg']) && $form['confirm_flg'] == $key ? true : false), 'id="confirm_flg"'); ?>
							<span><?= $val ?></span>
						</label>
						<?php } ?>
						<?php foreach ($select['trash_flg'] as $key => $val) { ?>
						<label class="label_check">
							<?= form_checkbox("cleaning_flg", $key, (is_array($form['cleaning_flg']) && $form['cleaning_flg'] == $key ? true : false), 'id="cleaning_flg"'); ?>
							<span><?= $val ?></span>
						</label>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="row align_center mb_40">
				<textarea name="comment" placeholder="コメント欄"></textarea>
			</div>
			<div class="max360"><label id="btn_record" class="btn conf_btn size_m">この製品を記録する</label></div>
			<input type="hidden" id="action" name="action" value="add">
			<input type="hidden" id="start_date" name="start_date" value="<?= VarDisp($form['start_date']) ?>">
			<input type="hidden" id="worker1" name="worker1" value="">
			<input type="hidden" id="worker2" name="worker2" value="">
		</form>
	</div><!--/.container-->


	<!-- キーボード モーダルウィンドウ -->
	<div class="modal_window no_print">
		<input id="keyboad_trigger" name="keyboad_trigger" type="checkbox" value="">
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
						<input type="button" id="enter_btn" name="enter_btn" value="入力" class="btn_submit">
					</div>
					<input type="reset" class="btn_reset">
				</form>
			</div>
		</div>
	</div><!--/.modal_window-->

	<!-- 作業者 モーダルウィンドウ -->
	<div class="modal_window no_print">
		<input id="comp_trigger" type="checkbox" value="">
		<div class="modal_overlay">
			<label for="comp_trigger" class="modal_close"></label>
			<div class="modal_cont">
				<label for="comp_trigger" class="btn_close"></label>
				<form action="">
					<p class="bold mb_20">作業者</p>
					<?= form_dropdown("input_worker1", $select['worker'], (isset($form['input_worker1']) ? $form['input_worker1'] : ""), 'id="input_worker1" class="mb_10"'); ?>
					<?= form_dropdown("input_worker2", $select['worker'], (isset($form['input_worker2']) ? $form['input_worker2'] : ""), 'id="input_worker2" class="mb_10"'); ?>

					<input type="button" value="記録完了" class="btn comp submit_btn">
				</form>
			</div>
		</div>
	</div><!--/.modal_window-->

	<!-- <?= ($placeData['type'] == 1 ? '計量記録' : '') ?><?= ($placeData['type'] == 2 ? '外注依頼記録' : '') ?><?= ($placeData['type'] == 3 ? '防錆記録' : '') ?> モーダルウィンドウ ------------------------------------------------>
	<div class="modal_window no_print">
		<input id="keiryo_trigger" type="checkbox">
		<div class="modal_overlay">
			<label for="keiryo_trigger" class="modal_close"></label>
			<div class="modal_cont record">
				<label for="keiryo_trigger" class="btn_close"></label>
				<form method="post" id="list_form" name="list_form" action="">
					<p class="font_18 navy bold mb_20">
						<span><?= ($placeData['type'] == 1 ? '計量記録' : '') ?><?= ($placeData['type'] == 2 ? '外注依頼記録' : '') ?><?= ($placeData['type'] == 3 ? '防錆記録' : '') ?></span>
						　<input type="button" value="印刷" class="btn print_btn second size_ss pickup no_print"></p>
					</p>
					<div class="row just_start align_center mb_10">
						<?= form_dropdown("start_y", $select['year'], (isset($form['start_y']) ? $form['start_y'] : ""), 'id="start_y" class="data date_s_y mr_10"'); ?>
						<?= form_dropdown("start_m", $select['month'], (isset($form['start_m']) ? $form['start_m'] : ""), 'id="start_m" class="data date_s_m mr_10"'); ?>
						<?= form_dropdown("start_d", $select['day'], (isset($form['start_d']) ? $form['start_d'] : ""), 'id="start_d" class="data date_s_d mr_10"'); ?>
						<span class="mr_10">～</span>
						<?= form_dropdown("end_y", $select['year'], (isset($form['end_y']) ? $form['end_y'] : ""), 'id="end_y" class="data date_e_y mr_10"'); ?>
						<?= form_dropdown("end_m", $select['month'], (isset($form['end_m']) ? $form['end_m'] : ""), 'id="end_m" class="data date_e_m mr_10"'); ?>
						<?= form_dropdown("end_d", $select['day'], (isset($form['end_d']) ? $form['end_d'] : ""), 'id="end_d" class="data date_e_d mr_10"'); ?>
						<input type="button" value="絞り込む" class="btn search_btn second size_s pickup no_print">
					</div>

					<div id="search_list" class="scroll">
						<?php if (isset($list) && count($list) > 0) { ?>
						<?php if ($placeData['type'] == 1) { ?>

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
						      <td><?= $list[$i]['start_date'] ?></td>
						      <td><?= $list[$i]['start_time'] ?></td>
						      <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
							  <?php if ($list[$i]['pdf_exists']) { ?>
								<td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
							  <?php } else { ?>
						      	<td><?= $list[$i]['number'] ?></td>
							  <?php } ?>
						      <td><?= $list[$i]['lot'] ?></td>
						      <td><?= VarNum($list[$i]['num']) ?></td>
						      <td><?= VarNum($list[$i]['packing']) ?></td>
						      <td><?= VarNum($list[$i]['total_num']) ?></td>
						      <td>
								<?= $list[$i]['worker1_name'] ?>
								<?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
							  </td>
						    </tr>
							<?php } ?>
						  </tbody>
						</table>

						<?php } elseif ($placeData['type'] == 2) { ?>

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
							<?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
						    <tr>
						      <td><?= $list[$i]['start_date'] ?></td>
						      <td><?= $list[$i]['start_time'] ?></td>
						      <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
							  <?php if ($list[$i]['pdf_exists']) { ?>
								<td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
							  <?php } else { ?>
						      	<td><?= $list[$i]['number'] ?></td>
							  <?php } ?>
						      <td><?= $list[$i]['lot'] ?></td>
						      <td><?= VarNum($list[$i]['num']) ?></td>
						      <td><?= VarNum($list[$i]['packing']) ?></td>
						      <td><?= $list[$i]['continue_flg_name'] ?></td>
						      <td>
								<?= $list[$i]['worker1_name'] ?>
								<?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
							  </td>
						    </tr>
							<?php } ?>
						  </tbody>
						</table>

						<?php } elseif ($placeData['type'] == 3) { ?>

						<table class="record">
						  <tbody>
						    <tr>
						      <th>作業日</th>
						      <th>時間</th>
						      <th>場所・秤</th>
						      <th>品番</th>
						      <th>ロット</th>
						      <th>数量</th>
						      <th>継続フラグ</th>
						      <th>作業者</th>
						    </tr>
							<?php for ($i = 0, $no = 1, $n = count($list); $i < $n; $i ++, $no ++) { ?>
						    <tr>
						      <td><?= $list[$i]['start_date'] ?></td>
						      <td><?= $list[$i]['start_time'] ?></td>
						      <td><?= $list[$i]['place_name'] ?>・<?= $list[$i]['place_scale'] ?></td>
							  <?php if ($list[$i]['pdf_exists']) { ?>
								<td><a class="pdf_link"><?= $list[$i]['number'] ?></a></td>
							  <?php } else { ?>
						      	<td><?= $list[$i]['number'] ?></td>
							  <?php } ?>
						      <td><?= $list[$i]['lot'] ?></td>
						      <td><?= VarNum($list[$i]['num']) ?></td>
						      <td><?= $list[$i]['continue_flg_name'] ?></td>
						      <td>
								<?= $list[$i]['worker1_name'] ?>
								<?= ($list[$i]['worker2_name'] ? '・' . $list[$i]['worker2_name'] : '') ?>
							  </td>
						    </tr>
							<?php } ?>
						  </tbody>
						</table>

						<?php } ?>

						<?php } else { ?>
							<div>no list</div>
						<?php } ?>
					</div><!--/.scroll-->
				</form>
			</div><!--/.modal_cont-->
		</div><!--/.modal_overlay-->
	</div><!--/.modal_window-->
</main>
<div>
	<p id="search_print_title" class="font_18 navy bold mb_20"></p>
	<div id="search_print_date" class="row just_start align_center mb_10">
		<?= form_dropdown("start_y_print", $select['year'], "", 'id="start_y_print" class="data mr_10"'); ?>
		<?= form_dropdown("start_m_print", $select['month'], "", 'id="start_m_print" class="data mr_10"'); ?>
		<?= form_dropdown("start_d_print", $select['day'], "", 'id="start_d_print" class="data mr_10"'); ?>
		<span class="mr_10">～</span>
		<?= form_dropdown("end_y_print", $select['year'], "", 'id="end_y_print" class="data mr_10"'); ?>
		<?= form_dropdown("end_m_print", $select['month'], "", 'id="end_m_print" class="data mr_10"'); ?>
		<?= form_dropdown("end_d_print", $select['day'], "", 'id="end_d_print" class="data mr_10"'); ?>
	</div>
	<div id="search_print_list" class="scroll"></div>

</div>
</body>
</html>