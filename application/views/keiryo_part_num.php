<?php if ($type_m) { ?>
<div class="lot mr_30">
	<label for="keyboad_trigger" class="btn lot_btn second size_m mb_10">ロット</label>
	<span>No.</span><input type="text" id="lot" name="lot" value="<?= VarDisp($product['lot']) ?>" class="txt_center disa">
</div>
<div class="piece mr_30">
	<label for="keyboad_trigger" class="btn num_btn second size_m mb_10">員数</label>
	<input type="tel" id="member_num" name="member_num" value="<?= VarDisp($product['member_num']) ?>" class="txt_center disa">
</div>
<div class="pack mr_60">
	<label for="keyboad_trigger" class="btn pack_btn second size_m mb_10">荷姿数量</label>
	<input type="tel" id="packing_num" name="packing_num" value="<?= VarDisp($product['packing_num']) ?>" class="txt_center disa">
</div>
<div class="total">
	<p class="mb_10">数量</p>
	<div class="total_number">0</div>
</div>
<?php } elseif ($type_o) { ?>
<div class="lot mr_30">
	<label for="keyboad_trigger" class="btn lot_btn second size_m mb_10">ロット</label>
	<span>No.</span><input type="text" id="lot" name="lot" value="<?= VarDisp($product['lot']) ?>" class="txt_center disa">
</div>
<div class="piece mr_30">
	<label for="keyboad_trigger" class="btn num_btn second size_m mb_10">現場エフ数量</label>
	<input type="tel" id="f_num" name="f_num" value="<?= VarDisp($product['f_num']) ?>" class="txt_center disa">
</div>
<div class="pack mr_60">
	<label for="keyboad_trigger" class="btn pack_btn second size_m mb_10">実荷姿数量</label>
	<input type="tel" id="packing_num_total" name="packing_num_total" value="<?= VarDisp($product['packing_num_total']) ?>" class="txt_center disa">
</div>
<?php } ?>