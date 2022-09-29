$(function () {
    ChangeSubmitBtn();
    ChangeTotalNum();
    InitComma();
    $(document).on('keyup', '#member_num', function() {
        ChangeTotalNum();
        ValidNum();
    });
    $(document).on('keyup', '#packing_num', function() {
        ChangeTotalNum();
        ValidPack();
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
        //if ($('#input_number').val().length < strLenMax) {
            $('#input_number').val($('#input_number').val() + $(this).find('span').text());
        //}
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
    $(document).on('click', '#enter_btn', function() {
        if ($('#keyboad_trigger').val() != '') {
            $('#' + $('#keyboad_trigger').val()).val($('#input_number').val());
            $('#input_number').val('');
            $('#keyboad_trigger').val('');
        }
        $('#keyboad_trigger').prop('checked', false).change();
        ChangeTotalNum();
    });
    /**
        * バーコードスキャンボタン
        */
    $('.barcode_btn').click(function() {
        scanFlg = true;
        // フォーカスする
        $('#inputCode').focus();
        LayerStart();
    });
    $(document).on('keypress', '#inputCode', function(e) {
        if (scanFlg) {
            // 改行処理
            if (e.charCode == 13) {
                var ajaxUrl = 'keiryo/ajax_code';
                var ajaxObj = {
                    input_code : $('#inputCode').val(),
                    type : <?= $placeData['type'] ?>,
                };
                AjaxAction(ajaxUrl, ajaxObj, BarcodFdAction);
            }
        }
    });
    //テキストボックスのフォーカスが外れたら発動
    $('#inputCode').blur(function() {
        ChangeSubmitBtn();
        LayerEnd();
        scanFlg = false;
    });
    $(document).on('keyup', '#number,#lot,#member_num,#packing_num,#f_num,#packing_num_total', function() {
        ChangeSubmitBtn();
    });
    $('#confirm_flg,#cleaning_flg').on('change', function() {
        ChangeSubmitBtn();
    });

    $(document).on('blur', '#member_num,#packing_num,#f_num,#packing_num_total,#bousei_num', function() {
        $(this).val(addFigure($(this).val()));
    });


    $(document).on('focus', '#member_num,#packing_num,#f_num,#packing_num_total,#bousei_num', function() {
        $(this).val(delFigure($(this).val()));
    });


    $(document).on('click', '.pdf_btn', function() {
        var ajaxUrl = 'keiryo/ajax_pdf';
        var ajaxObj = {
            number : $('#number').val(),
            type : <?= $placeData['type'] ?>,
        };
        AjaxAction(ajaxUrl, ajaxObj, BarcodFdAction);
    });
    $(document).on('click', '.pdf_link', function() {
        var ajaxUrl = 'keiryo/ajax_pdf';
        var ajaxObj = {
            number : $(this).text()
        };
        AjaxAction(ajaxUrl, ajaxObj, BarcodFdAction);
    });
    $(document).on('click', '.lot_btn', function() {
        $('#keyboad_trigger').val('lot');
    });
    $(document).on('click', '.num_btn', function() {
        <?php if ($placeData['type'] == 1) { ?>
        $('#keyboad_trigger').val('member_num');
        <?php } elseif ($placeData['type'] == 2) { ?>
        $('#keyboad_trigger').val('f_num');
        <?php } elseif ($placeData['type'] == 3) { ?>
        $('#keyboad_trigger').val('bousei_num');
        <?php } ?>
    });
    $(document).on('click', '.pack_btn', function() {
        <?php if ($placeData['type'] == 1) { ?>
        $('#keyboad_trigger').val('packing_num');
        <?php } elseif ($placeData['type'] == 2) { ?>
        $('#keyboad_trigger').val('packing_num_total');
        <?php } ?>
    });
    $('#btn_record').click(function () {
        if (CheckRegist1()) {
            $('#comp_trigger').prop('checked', true).change();
        }
        else {
            $('#comp_trigger').prop('checked', false).change();
            ValidNumber();
            ValidNum();
            ValidPack();
        }
    });
    /**
        * 登録ボタン
        */
    $('.submit_btn').click(function () {
        if (CheckRegist2()) {
            // 書込み処理
            $('#worker1').val($('#input_worker1').val());
            $('#worker2').val($('#input_worker2').val());
            // submit処理
            $('#operation_form').attr( 'action', '<?= SiteDir(); ?>keiryo/input/<?= $placeData['code'] ?>' );
            $('#operation_form').submit();
        }
        else {
            // エラー表示
            ValidWorker();
        }

        if ($('#keyboad_trigger').val() != '') {
            $('#' + $('#keyboad_trigger').val()).val($('#input_number').val());
            $('#input_number').val('');
            $('#keyboad_trigger').val('');
        }
        $('#keyboad_trigger').prop('checked', false).change();
    });
    $('#input_worker1,#input_worker2').on('change', function() {
        ValidWorker();
    });
    /**
        * 検索ボタン
        */
    $('.search_btn').click(function () {
        var ajaxUrl = 'keiryo/ajax_list';
        var ajaxObj = {
            start_y : $('#start_y').val(),
            start_m : $('#start_m').val(),
            start_d : $('#start_d').val(),
            end_y : $('#end_y').val(),
            end_m : $('#end_m').val(),
            end_d : $('#end_d').val(),
            type : <?= $placeData['type'] ?>,
        };
        AjaxAction(ajaxUrl, ajaxObj);
    });
	/**
     * 印刷ボタン
     */
    $(document).on('click', '.print_btn', function() {
		$('#search_print_title').text($(this).parent().find('span').text());
		$('#start_y_print').val($(this).parent().parent().find('.row').find('.date_s_y').val());
		$('#start_m_print').val($(this).parent().parent().find('.row').find('.date_s_m').val());
		$('#start_d_print').val($(this).parent().parent().find('.row').find('.date_s_d').val());
		$('#end_y_print').val($(this).parent().parent().find('.row').find('.date_e_y').val());
		$('#end_m_print').val($(this).parent().parent().find('.row').find('.date_e_m').val());
		$('#end_d_print').val($(this).parent().parent().find('.row').find('.date_e_d').val());
		$('#search_print_list').html($(this).parent().parent().find('.scroll').html());

		window.print();
    });
});

/**
 * バリデーション処理
 */
// 品番エラーチェック
function ValidNumber () {
    if ($('#number').val() != '') {
        $('#number').removeClass('form_error_textbox');
    }
    else {
        $('#number').addClass('form_error_textbox');
    }
}
// 員数 or 現場エフ数量
function ValidNum () {
    <?php if ($placeData['type'] == 1) { ?>
        var targetSel = '#member_num';
    <?php } elseif ($placeData['type'] == 2) { ?>
        var targetSel = '#f_num';
    <?php } elseif ($placeData['type'] == 3) { ?>
        var targetSel = '#bousei_num';
    <?php } ?>
    if (
        $(targetSel).val() != '' &&
        $(targetSel).val().match(/^[0-9,]+$/)
    ) {
        $(targetSel).removeClass('form_error_textbox');
    }
    else {
        $(targetSel).addClass('form_error_textbox');
    }
}
// 荷姿数量 or 実荷姿数量
function ValidPack () {
    <?php if ($placeData['type'] == 1) { ?>
        var targetSel = '#packing_num';
    <?php } elseif ($placeData['type'] == 2) { ?>
        var targetSel = '#packing_num_total';
    <?php } ?>
    if (
        $(targetSel).val() != '' &&
        $(targetSel).val().match(/^[0-9,]+$/)
    ) {
        $(targetSel).removeClass('form_error_textbox');
    }
    else {
        $(targetSel).addClass('form_error_textbox');
    }
    <?php if ($placeData['type'] == 3) { ?>
        $(targetSel).removeClass('form_error_textbox');
    <?php } ?>
}
// 作業者エラーチェック
function ValidWorker () {
    if (
        $('#input_worker1').val() == '' &&
        $('#input_worker2').val() == ''
    ) {
        $('#input_worker1').addClass('form_error_textbox');
        $('#input_worker2').addClass('form_error_textbox');
    }
    else if (
        $('#input_worker1').val() != '' &&
        $('#input_worker2').val() != '' &&
        $('#input_worker1').val() == $('#worker2').val()
    ) {
        $('#input_worker2').addClass('form_error_textbox');
    }
    else {
        $('#input_worker1').removeClass('form_error_textbox');
        $('#input_worker2').removeClass('form_error_textbox');
    }
}



function ChangeTotalNum(){
    var num1 = delFigure($('#member_num').val());
    var num2 = delFigure($('#packing_num').val());
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
/**
    * バーコードボタンFB関数
    */
function BarcodFdAction(){
    PdfLinkAction();
    ChangeSubmitBtn();
    ChangeTotalNum();
    $('#inputCode').blur();
    $('#inputCode').val('');

}
/**
    * PDF表示処理
    */
function PdfLinkAction(){
    var inputCode = $('#inputCode').val();
    if (inputCode) {
        window.open(inputCode);
    }
}
/**
    * 記録するボタン表示変更
    */
function ChangeSubmitBtn(){
    if (CheckRegist1()) {
        $('#btn_record').removeClass('hidden');
    }
    else {
        $('#btn_record').addClass('hidden');
    }
}
function CheckRegist1(){
    var returnVal = false;
    var number = $('#number').val();
    <?php if ($placeData['type'] == 1) { ?>
    var num = $('#member_num').val();
    var pack = $('#packing_num').val();
    <?php } elseif ($placeData['type'] == 2) { ?>
    var num = $('#f_num').val();
    var pack = $('#packing_num_total').val();
    <?php } elseif ($placeData['type'] == 3) { ?>
    var num = $('#bousei_num').val();
    <?php } ?>
    var chk1 = $('#confirm_flg').prop('checked');
    var chk2 = $('#cleaning_flg').prop('checked');
    if (
        number &&
        num &&
        (num.match(/^[0-9,]+$/)) &&
        <?php if ($placeData['type'] == 1 || $placeData['type'] == 2) { ?>
        pack &&
        (pack.match(/^[0-9,]+$/)) &&
        <?php } ?>
        chk1 &&
        chk2
    ) {
        returnVal = true;
    }
    return returnVal;
}
function CheckRegist2(){
    var returnVal = false;
    if (
        $('#input_worker1').val() != '' &&
        $('#input_worker1').val() != $('#input_worker2').val()
    ) {
        returnVal = true;
    }
    return returnVal;
}
function DispError(){
    var number = $('#number').val();
    <?php if ($placeData['type'] == 1) { ?>
    var num = $('#member_num').val();
    var pack = $('#packing_num').val();
    <?php } elseif ($placeData['type'] == 2) { ?>
    var num = $('#f_num').val();
    var pack = $('#packing_num_total').val();
    <?php } elseif ($placeData['type'] == 3) { ?>
    var num = $('#bousei_num').val();
    <?php } ?>
    var chk1 = $('#confirm_flg').prop('checked');
    var chk2 = $('#cleaning_flg').prop('checked');
}

var suggestData = [];
var suggestUrl = [];



/**
 * 数値の3桁カンマ区切り
 * 入力値をカンマ区切りにして返却
 * [引数]   numVal: 入力数値
 * [返却値] String(): カンマ区切りされた文字列
 */
function addFigure(numVal) {
    // 空の場合そのまま返却
    if (numVal == ''){
        return '';
    }
    if (numVal) {
        // 全角から半角へ変換し、既にカンマが入力されていたら事前に削除
        numVal = toHalfWidth(numVal).replace(/,/g, "").trim();
    }
    // 数値でなければそのまま返却
    if ( !/^[+|-]?(\d*)(\.\d+)?$/.test(numVal) ){
        return numVal;
　　}
    // 整数部分と小数部分に分割
    var numData = numVal.toString().split('.');
    // 整数部分を3桁カンマ区切りへ
    numData[0] = Number(numData[0]).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    // 小数部分と結合して返却
    return numData.join('.');
}

/**
 * カンマ外し
 * 入力値のカンマを取り除いて返却
 * [引数]   strVal: 半角でカンマ区切りされた数値
 * [返却値] String(): カンマを削除した数値
 */
function delFigure(strVal){
    if (strVal) {
        return strVal.replace( /,/g , "" );
    }
    else {
        return ;
    }
}

/**
 * 全角から半角への変革関数
 * 入力値の英数記号を半角変換して返却
 * [引数]   strVal: 入力値
 * [返却値] String(): 半角変換された文字列
 */
function toHalfWidth(strVal){
    if (strVal) {
        // 半角変換
        var halfVal = strVal.replace(/[！-～]/g,
            function( tmpStr ) {
                // 文字コードをシフト
                return String.fromCharCode( tmpStr.charCodeAt(0) - 0xFEE0 );
            }
        );
    }
    return halfVal;
}


function InitComma () {
    $('#member_num').val(addFigure($('#member_num').val()));
    $('#packing_num').val(addFigure($('#packing_num').val()));
    $('#f_num').val(addFigure($('#f_num').val()));
    $('#packing_num_total').val(addFigure($('#packing_num_total').val()));
    $('#bousei_num').val(addFigure($('#bousei_num').val()));
}