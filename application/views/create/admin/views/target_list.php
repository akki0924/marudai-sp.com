<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $comment ?>管理｜\<\?= $const['site_title_name'] \?\></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS-->
<link href="\<\?= SiteDir(); \?\>css/\<\?= JqueryUiCssFile() \?\>" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="\<\?= SiteDir(); \?\>css/style_admin.css">
<link rel="stylesheet" href="\<\?= SiteDir(); \?\>css/reset.css">
<link rel="stylesheet" type="text/css" href="\<\?= SiteDir(); \?\>form/css">
<!--JavaScript-->
<script src="\<\?= SiteDir(); \?\>js/\<\?= JqueryFile() \?\>"></script>
<script src="\<\?= SiteDir(); \?\>js/\<\?= JqueryUiJsFile() \?\>"></script>
<script type="text/javascript" src="\<\?= SiteDir(); \?\>js/nav.js"></script>
<script type="text/javascript" src="\<\?= SiteDir(); \?\>form/js"></script>
<script language="JavaScript">
$(function() {
	$('.edit_btn').click(function() {
		$('#id').val( $(this).data('id') );
		$('#operation_form').attr( 'action', '\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>/input' );
		$('#operation_form').submit();
	});
	$('#list_area').sortable({
		items: "tr",
		cursor: 'ns-resize',
		axis: 'y',
		placeholder: 'ui-state-highlight',
		start: function(event, ui){
			sortId = ui.item[0]['dataset'].id;
			sortRow = ui.item[0]['sectionRowIndex'];
            ui.placeholder.height(ui.helper.outerHeight());
        },
        helper: fixPlaceHolderWidth,
		containment:'parent',
		cancel:'.list_header',
		update: function(event, ui){
			var listLen = $("#list_area").children().length;
			if (
				sortId == ui.item[0]['dataset'].id &&
				sortRow != ui.item[0]['sectionRowIndex']
			) {
				sortRow = ui.item[0]['sectionRowIndex'];
				var ajaxUrl = '\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>/sort';
				var dataList = {
					'id':sortId,
					'sort_id':sortRow
				};
				AjaxAction(ajaxUrl, dataList);
			}
		}
	});
	function fixPlaceHolderWidth(event, ui){
        ui.children().each(function(){
            $(this).width($(this).width());
        });
        return ui;
    };
});
</script>

</head>

<body>
<header>
	<div class="container">
		<div class="row align_center">
			<div class="logo">\<\?= $const['site_title_name'] \?\></div>
			<div class="member"><a href="\<\?= SiteDir(); \?\>\<\?= $const['admin_dir'] \?\>/index/logout" class="btn frame shortest">ログアウト</a></div>
		</div>
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
	<div class="bg_gray pd20 border"><h1>管理者画面</h1></div>

	<section id="management">
		<div class="container">
			<div class="row mb_80">
<?php for ($i = 0, $n = count($tableList); $i < $n; $i ++) { ?>
				<div class="col<?= $n ?>"><a href="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $tableList[$i]['targetName'] ?>" class="btn mng<?= ($tableList[$i]['name'] == $tableName ? ' frame' : '') ?>"><?= $tableList[$i]['comment'] ?>管理</a></div>
<?php } ?>
			</div>
		</div><!--/.container-->

		\<\?php if (form_error($const['valid_add_name'])) : \?\>
			<span>\<\?= form_error($const['valid_add_name']); \?\></span><br>
		\<\?php endif; \?\>

		<div class="container">
		<form method="post" id="operation_form" name="operation_form" action="\<\?= SiteDir(); \?\>\<\?= $const['access_admin_dir'] \?\>/<?= $targetName ?>">
			<h2 class="mb_40"><?= $comment ?>管理</h2>
			<div class="scroll">
				<table class="management">
					<tbody id="list_area">
					\<\?php if (isset($list) && count($list) > 0) { \?\>
						<tr class="list_header">
							<th>ID</th>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if ($tableSel[$i]['comment'] != '') { ?>
							<th><?= $tableSel[$i]['comment'] ?></th>
<?php } ?>
<?php } ?>
							<th>登録日時</th>
							<th>&nbsp;</th>
						</tr>
						\<\?php for ($i = 0, $n = count($list); $i < $n; $i ++) { \?\>
						<tr data-id="\<\?= $list[$i]['id'] \?\>">
							<td>\<\?= $list[$i]['id'] \?\></td>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if ($tableSel[$i]['comment'] != '') { ?>
<?php if ($tableSel[$i]['name'] != 'status') { ?>
							<td>\<\?= $list[$i]['<?= $tableSel[$i]['name'] ?>'] \?\></td>
<?php } else { ?>
							<td>\<\?= $list[$i]['status_name'] \?\></td>
<?php } ?>
<?php } ?>
<?php } ?>
							<td>\<\?= $list[$i]['regist_date'] \?\></td>
							<td>
								<a class="btn frame shortest edit_btn" data-id="\<\?= $list[$i]['id'] \?\>">編集</a>
							</td>
						</tr>
						\<\?php } \?\>
					\<\?php } else { \?\>
						<tr><td bgcolor="#F2F2F2" align="center" valign="middle">no list</td></tr>
					\<\?php } \?\>
					</tbody>
				</table>
			</div><!--./scroll-->
			<input type="hidden" name="id" id="id">
			<input type="hidden" name="action" id="action">
		</form>
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
