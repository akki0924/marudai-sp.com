$(function () {
    ChangeSubmitBtn();
    ChangeTotalNum();
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
                    type : <?= $placeData['place'] ?>,
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
    $(document).on('click', '.lot_btn', function() {
        $('#keyboad_trigger').val('lot');
    });
    $(document).on('click', '.num_btn', function() {
        <?php if ($placeData['type'] == 1) { ?>
        $('#keyboad_trigger').val('member_num');
        <?php } else { ?>
        $('#keyboad_trigger').val('f_num');
        <?php } ?>
    });
    $(document).on('click', '.pack_btn', function() {
        <?php if ($placeData['type'] == 1) { ?>
        $('#keyboad_trigger').val('packing_num');
        <?php } else { ?>
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
            type : <?= $placeData['place'] ?>,
        };
        AjaxAction(ajaxUrl, ajaxObj);
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
    <?php } else { ?>
        var targetSel = '#f_num';
    <?php } ?>
    if (
        $(targetSel).val() != '' &&
        $(targetSel).val().match(/^[0-9]+$/)
    ) {
        console.log('OK');
        $(targetSel).removeClass('form_error_textbox');
    }
    else {
        console.log('NG');
        $(targetSel).addClass('form_error_textbox');
    }
}
// 荷姿数量 or 実荷姿数量
function ValidPack () {
    <?php if ($placeData['type'] == 1) { ?>
        var targetSel = '#packing_num';
    <?php } else { ?>
        var targetSel = '#packing_num_total';
    <?php } ?>
    if (
        $(targetSel).val() != '' &&
        $(targetSel).val().match(/^[0-9]+$/)
    ) {
        $(targetSel).removeClass('form_error_textbox');
    }
    else {
        $(targetSel).addClass('form_error_textbox');
    }
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
    <?php } else { ?>
    var num = $('#f_num').val();
    var pack = $('#packing_num_total').val();
    <?php } ?>
    var chk1 = $('#confirm_flg').prop('checked');
    var chk2 = $('#cleaning_flg').prop('checked');
    if (
        number &&
        num &&
        (num.match(/^[0-9]+$/)) &&
        pack &&
        (pack.match(/^[0-9]+$/)) &&
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
    <?php } else { ?>
    var num = $('#f_num').val();
    var pack = $('#packing_num_total').val();
    <?php } ?>
    var chk1 = $('#confirm_flg').prop('checked');
    var chk2 = $('#cleaning_flg').prop('checked');
}

var suggestData = [];
var suggestUrl = [];
