$(function () {
    ChangeTotalNum();

    $(document).on('keyup', '#member_num', function() {
        ChangeTotalNum();
        ValidNum();
    });
    $(document).on('keyup', '#packing_num', function() {
        ChangeTotalNum();
        ValidPack();
    });
    /**
    * 検索ボタン
    */
    $('.search_btn').click(function () {
        var ajaxUrl = '<?= $const['access_admin_dir'] ?>/work/ajax_list';
        var ajaxObj = {
            place : $('#place').val(),
            number : $('#number').val(),
            lot : $('#lot').val(),
            start_y : $('#start_y').val(),
            start_m : $('#start_m').val(),
            start_d : $('#start_d').val(),
            end_y : $('#end_y').val(),
            end_m : $('#end_m').val(),
            end_d : $('#end_d').val(),
            type : <?= $placeType ?>,
        };
        AjaxAction(ajaxUrl, ajaxObj);
    });
    $(document).on('click', '.edit_btn', function() {
        $('#id').val($(this).data('id'));
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/input/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $(document).on('click', '.del_btn', function() {
        // IDをセット
        $('#id').val($(this).data('id'));
        var mainObj = {
            title:'情報を削除します',
            body:'情報を削除します。<br>よろしいですか？'
        };
		// ボタン情報をセット
		var buttonsObj = [
			{
				text : '削除',
				click: function() {
					// 削除処理
                    $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/del/<?= $placeType ?>' );
                    $('#operation_form').submit();
					$(this).dialog("close");
                }
			},
			{
				text : 'キャンセル',
				click: function() {
					$(this).dialog("close");
				}
			}
        ];
        ShowDialog(mainObj, buttonsObj);
    });
    $('.up_btn').click(function() {
        $('#action').val('comp');
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/csv/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $('.dl_btn').click(function() {
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/csv_dl/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $('.csv_btn').click(function() {
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/up_dl/<?= $placeType ?>' );
        $('#id').val($(this).data('id'));
        $('#operation_form').submit();
    });
    $('.conf_btn').click(function() {
        $('#action').val('conf');
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/input/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $('.list_btn').click(function() {
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $('.comp_btn').click(function() {
        $('#action').val('comp');
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/input/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
    $('.back_btn').click(function() {
        $('#action').val('back');
        $('#operation_form').attr( 'action', '<?= SiteDir(); ?><?= $const['access_admin_dir'] ?>/work/input/<?= $placeType ?>' );
        $('#operation_form').submit();
    });
	/**
     * 印刷ボタン
     */
    $(document).on('click', '.print_btn', function() {
		window.print();
    });
});
function ChangeTotalNum(){
    var num1 = $('#member_num').val();
    var num2 = $('#packing_num').val();
    if (
        $.isNumeric(num1) &&
        $.isNumeric(num2)
    ) {
        var totalNum = num1 * num2;
        $('.total_number').text(totalNum.toLocaleString());
    }
    else {
        $('.total_number').text('');
    }
}
