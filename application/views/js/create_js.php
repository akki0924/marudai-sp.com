// 読込み完了時
$(function() {
    // ローディングタグ生成
    CreateLorder();
});
/**
 * AJAX処理
 *
 * @param mixed url:実行処理用URL
 */
// AJAX処理
function AjaxAction(url) {
    $(document).ajaxSend(function() {
        $("<?= $const['sel_loader'] ?>").fadeIn(<?= $const['time_loading_speed'] ?>);
    });
    $.ajax({
        url: '<?= SiteDir(); ?>' + url,
        dataType:'json',
        cache: false
    })
    .then(
        // 成功時
        function (returnData) {
            // 画面への反映フラグ
            if (returnData['<?= $const['key_ajax_reaction_flg'] ?>']) {
                $.each(
                    returnData['<?= $const['key_ajax_reaction'] ?>'],
                    function( key, value ) {
                        var targetSel = ( $('#' + key).length ? '#' + key : '.' + key );
                        $(targetSel).html(value);
                    }
                );
            }
            // ローディング解除
            setTimeout(function(){
                $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
            },<?= $const['time_loading_timeout'] ?>);
            console.log('loading end2');
        },
        // エラー時
        function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            // エラー処理
            setTimeout(function(){
                $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
            },<?= $const['time_loading_timeout'] ?>);
        }
    );
}
/**
 * ローダー生成
 */
function CreateLorder ()
{
    // ローダー生成
    var loader = $('<div />').attr( 'id', '<?= $const['sel_loader_name'] ?>' )
        .append(
            $('<div />').attr( 'class', '<?= $const['sel_loader_cv_name'] ?>' )
            .append(
                $('<span />').attr( 'class', '<?= $const['sel_loader_spinner_name'] ?>' )
            )
        );
    // BODY要素の最後に追加
    $('body').append(loader);
}
/**
 * ローディング表示処理
 */
function LordingStart() {
    $("<?= $const['sel_loader'] ?>").fadeIn(<?= $const['time_loading_speed'] ?>);
}
/**
 * ローディング解除処理
 */
function LordingEnd() {
    setTimeout(function(){
        $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
    },<?= $const['time_loading_timeout'] ?>);
}
/**
 * ダイアログ生成、表示、及び関連プログラム
 *
 * jQuery UIダイアログを利用して、簡易的にダイアログ表示
 * ダイアログ内のボタン押下後の動作もセット可能
 * jQuery UI 1.12.1 動作
 *
 * @param object mainObj
 *          string  title       タイトル
 *          string  body        本文
 *          int     width       横幅
 *          int     height      縦幅
 *          boolean draggable   ドラッグ
 *          boolean close       閉じる
 * @param object buttonsObj
 *          string      text    ボタン表示名
 *          function    click   ボタン押下後の動作
 */
function ShowDialog ( mainObj = {}, buttonsObj = {}) {
    // ボタン情報未セット
    if ( Object.keys( buttonsObj ).length == 0 ) {
        // 基本ボタン情報セット
        buttonsObj = {
            '閉じる': function() {
                $(this).dialog("close");
            }
        }
    }
    // オプション情報をセット
    var options = {};
    options['modal'] = true;
    options['title'] = mainObj['title'];                            // タイトル
    options['buttons'] = buttonsObj;                                // ボタン情報(オブジェクト形式)
    if (mainObj['width']) options['width'] = mainObj['width'];      // 横幅（指定時）
    if (mainObj['height']) options['height'] = mainObj['height'];   // 縦幅（指定時）
    if (mainObj['draggable'] != null) {
        options['draggable'] = mainObj['draggable'];                // ドラッグ可否情報
    }
    else if ( ! mainObj['draggable'] ) {
        options['draggable'] = false;                               // ドラッグ不可
    }
    if (mainObj['close']) options['close'] = mainObj['close'];      // ダイアログ閉じる

    // 対象セレクターIDセット
    if ( mainObj['id'] ) {
        var dialogDiv;
        // 対象セレクター非存在
        if ( ! $( '#' + mainObj['id'] ) ) {
            dialogDiv = $('<div />').attr( 'id', mainObj['id'] );   // 対象セレクターを生成
        }
        else {
            dialogDiv = $( '#' + mainObj['id'] );
        }
        // 本文セット
        if ( mainObj['body'] != '' ) {
            dialogDiv.html( mainObj['body'] );
        }
        // ダイアログ表示
        dialogDiv.dialog( options );
    }
    // 対象セレクターID未セット
    else {
        if ( mainObj['body'] != '' ) {
            // DIV情報を変数に代入
            var dialogDiv = '<div>' + mainObj['body'] + '</div>';
        }
        else {
            // DIV情報を変数に代入
            var dialogDiv = '<div></div>';
        }
        // オプション情報を追加
        options['close'] = function() { $(this).remove(); };
        // ダイアログ表示
        $( dialogDiv ).dialog( options );
    }
}
/**
 * Form要素のタイプを取得（エレメント名より）
 *
 * @param string targetName:対象エレメント名
 */
function GetFormElemType( targetName ) {
    // 返値を初期化
    var returnVal = '';
    // input, select
    if ( $('[name="' + targetName + '"]').attr('type') != null ) {
        returnVal = $('[name="' + targetName + '"]').attr('type');
    }
    // textarea
    else if ( $('[name="' + targetName + '"]').text() != null ) {
        returnVal = 'textarea';
    }
    return returnVal;
}
/*====================================================================
    関数名： GetFormElemVal
    概　要： Form要素の値を取得（エレメント名より）
    引　数： targetName： 対象エレメント名
*/
/**
 * Form要素の値を取得（エレメント名より）
 *
 * @param string targetName:対象エレメント名
 */
function GetFormElemVal( targetName ) {
    // 返値を初期化
    var returnVal = false;
    // タイプを取得
    var type = GetFormElemType( targetName );
    // text, select, textarea
    if (
        type == "text" ||
        type == "select" ||
        type == "textarea"
    ) {
        returnVal = $('[name="' + targetName + '"]').val();
    }
    // radio
    else if ( type == "radio" ) {
        returnVal = $('input[name="' + targetName + '"]:checked').val();
    }
    // checkbox
    else if ( type == "checkbox" ) {
        returnVal = $('input[name="' + targetName + '"]:checked').map(function(){
                        return $(this).val();
                    }).get();
    }
    // hidden
    if ( type == "hidden" ) {
        // オブジェクト型
        returnVal = $('[name="' + targetName + '"]').map(function(){
                        return $(this).val();
                    }).get();
        // 単独テキスト型
        returnVal = ( Object.keys(returnVal).length == 1 ? returnVal[0] : returnVal );
    }
// console.log (returnVal);
    return returnVal;
}

