$(function() {
    /**
     * submit処理
     * .submit_action要素をclickし、カスタムデータで値を付与する
     * 以下カスタムデータ
     *  form_id：フォームID
     *  action：フォームアクション
     *  target_○○：クエリ情報
     * @return submit処理するため返値無し
     */
    $(document).on('click', '.submit_action', function() {
        // カスタムデータ属性
        var dataList = $(this).data();
        // カスタムデータ属性がセットされていない場合
        if (Object.keys(dataList).length > 0) {
            // 値をセット用連想配列を宣言
            var dataTargetList = {};
            // ループ（カスタムデータ属性のみ取得）
            $.each(
                dataList,
                function(key, value) {
                    if (key.match(/target_/)) {
                        // dataTargetList[key.split('target_')[1]] = value;
                        dataTargetList[key] = value;
                    }
                }
            );
            // formセレクタ
            if (dataList.form_id) {
                var formSel = $('#' + dataList.form_id);
            } else {
                var formSel = $(this).parents('form');
            }
            // action情報を上書き
            if (dataList.action) {
                formSel.attr('action', dataList.action);
            }
            // クエリ情報をセット
            if (Object.keys(dataTargetList).length > 0) {
                // ループ（カスタムデータ属性：クエリ情報）
                $.each(
                    dataTargetList,
                    function(target_key, target_value) {
                        if (!$("#" + target_key).length) {
                            // hidden情報を動的に追記
                            $('<input>').attr({
                                type: 'hidden',
                                id: target_key,
                                name: target_key,
                                value: target_value
                            }).appendTo('form');
                        } else {
                            // hidden情報をに値をセット
                            $("#" + target_key).val(target_value);
                        }
                    }
                );
            }
            // サブミット処理
            formSel.submit();
        }
    });
});


// 基本情報
var siteDir = '/';
// ダイアログメッセージ
var dialogTitleDefault = 'info';
var dialogCompMsg = '情報の更新を完了しました。';
var dialogErrMsg = '情報の更新に失敗しました。';
var dialogCancelMsg = 'キャンセルしました。';
var dialogNgMsg = '必要な情報がセットされていません。';
// 各ID名
if ( ! idLorder )   var idLorder = 'loader';
if ( ! idLorderBg )   var idLorderBg = 'loader_bg';
    // 各セレクター
if ( ! selAddBtn )  var selAddBtn = '#add_btn';
if ( ! selListEditBtn ) var selListEditBtn = '.list_edit_btn';
if ( ! selListDelBtn )  var selListDelBtn = '.list_del_btn';
if ( ! selListLines )   var selListLines = '#list_lines';
if ( ! selLorder )   var selLorder = '#' + idLorder;
if ( ! selLorderBg )   var selLorderBg = '#' + idLorderBg;
var ErrMsgLeadStr = 'errors_';
/*====================================================================
    関数名： AjaxEditData
    概　要： Ajaxでデータを登録・編集
    引　数： targetObj  ：対象オブジェクト
             ajaxUrl    ：対象AJAX用URL
             callbackObj：コールバック部分データ
             no_loading:  ローディング画面を挟まないフラグ
*/
function AjaxEditData ( targetObj, ajaxUrl, callbackObj = {}, no_loading = false ) {
    // 返値にDeferredオブジェクトをセット
    var returnVal = false;

    // jQueryのAJAXファンクションを利用
    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: targetObj,
        dataType: 'json',
        beforeSend: function() {
            // ローディング表示
            if ( ! no_loading ) ShowLording ();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
//            alert (XMLHttpRequest + "<br>" + textStatus + "<br>" + errorThrown);
        },
        success: function( content ) {
            // 結果用の値が取得できた場合
            if ( content['result'] == true ) {
                // エラーメッセージを初期化
                ClearErrorMsg ();
                // 対象セレクタがセットされている場合
                if ( content['target_selector'] ) {
                    // 対象関数を再セット
                    if ( ! content['target_function'] ) content['target_function'] = 'html';
                    if ( ! no_loading ) {
                        if ( $.isArray( content['target_selector'] ) ) {
                            $.each( content['target_selector'][i], function( i, value ) {
                            // 情報の更新（アニメーション）
                                $(content['target_selector'][i]).fadeOut('10', function(){
                                    if ( content['target_value'] &&  content['target_value'][i] != null ) {
                                        $(this)[content['target_function']]( content['target_value'][i] ).fadeIn('10');
                                    }
                                    else {
                                        $(this)[content['target_function']]().fadeIn('10');
                                    }
                                });
                            });
                        }
                        else {
                            // 情報の更新（アニメーション）
                            $(content['target_selector']).fadeOut('10', function(){
                                if ( content['target_value'] != null ) {
                                    $(this)[content['target_function']]( content['target_value'] ).fadeIn('10');
                                }
                                else {
                                    $(this)[content['target_function']]().fadeIn('10');
                                }
                            });
                        }
                    }
                    else {
                        if ( $.isArray( content['target_selector'] ) ) {
                            $.each( content['target_selector'], function( i, value ) {
                                if ( content['target_value'] && content['target_value'][i] != null ) {
                                    // 情報の更新（アニメーション無し）
                                    $( content['target_selector'][i] )[content['target_function']]( content['target_value'][i] );
                                }
                                else {
                                    // 情報の更新（アニメーション無し）
                                    $( content['target_selector'][i] )[content['target_function']]();
                                }
                            });
                        }
                        else {
                            if ( content['target_value'] != null ) {
                                // 情報の更新（アニメーション無し）
                                $(content['target_selector'])[content['target_function']]( content['target_value'] );
                            }
                            else {
                                // 情報の更新（アニメーション無し）
                                $(content['target_selector'])[content['target_function']]();
                            }
                        }
                    }
                }
                // 対象エラーセレクタがセットされている場合
                if ( content['error_selector'] ) {
                    // エラー情報の更新
                    $(content['error_selector']).html( content['error_value'] );
                    // エラー情報の配置
                    var n = content['error_id'].length;
                    if ( n > 0 ) {
                        for ( var i = 0; i < n; i ++ ) {
                            SetErrorMsg ( content['error_id'][i] );
                        }
                    }
                }
                else {
                    returnVal = true;
                }
                if ( content['target_msg'] ) {
                    // ダイアログメッセージ表示
                    DisplayMsg( dialogTitleDefault, content['target_msg'] );
//                    ShowDialog ( dialogTitleDefault, content['target_msg'] );
                }
            }
        },
        complete: function() {
            // ローディング画面非表示
            if ( ! no_loading ) HiddenLording ();
            // コールバック部分
            if ( Object.keys( callbackObj ).length > 0 ) {
                // エラーメッセージ表示（フォームエラー時）
                if ( callbackObj.error.length > 0 ) {
                    for ( var i = 0, n = callbackObj.error.length; i < n; i ++ ) {
                        // エラーメッセージ表示位置調整
                        SetErrorMsg ( callbackObj.error[i] );
                    }
                }
                // ダイアログ閉じる（ダイアログ表示時）
                if ( callbackObj.dialog_id && returnVal ) {
                    $('#' + callbackObj.dialog_id).dialog("close");
                }
            }
        }
    });
// console.log('comp');
    return returnVal;
}
/*====================================================================
    関数名： AjaxGetData
    概　要： Ajaxでデータを取得
    引　数： targetObj  ：対象オブジェクト
             ajaxUrl    ：対象AJAX用URL
*/
function AjaxGetData ( targetObj, ajaxUrl ) {
    var returnVal = false;
// console.log ("start");
    // jQueryのAJAXファンクションを利用
    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: targetObj,
        dataType: 'json'
    }).done( function ( content ) {
        if ( content['target_selector'] ) {
            // 情報の更新（アニメーション）
            $(content['target_selector']).html( content['target_value'] );
        }
        returnVal = content;
    }).fail( function ( content ) {
        returnVal = content;
    });
// console.log('comp');
    return returnVal;
}
/*====================================================================
    関数名： PostFormAction
    概　要： JavaScript上でPOSTデータをセットし、遷移
    引　数： dataObj    ：対象データ（配列 - オブジェクト[name, value]）
             actionUrl  ：対象URL
*/
function PostFormAction ( dataObj, actionUrl ) {
    // フォームエレメントを生成
    var form = document.createElement('form');
    // フォーム情報をセット
    form.method = 'POST';
    form.action = actionUrl;
    
    // 対象データが配列 - オブジェクトの場合
    if ( Array.isArray( dataObj ? "true" : "false" ) ) {
        for ( var i = 0, n = dataObj.length; i < n; i ++ ) {
            var request = document.createElement('input');
            // inputデータに代入
            request.type = 'hidden';
            request.name = dataObj[i].name;
            request.value = dataObj[i].value;
            // formに追加
            form.appendChild(request);
        }
    }
    // 対象データがオブジェクトの場合
    else if ( $.isPlainObject ( dataObj ) ) {
        var request = document.createElement('input');
        // inputデータに代入
        request.type = 'hidden';
        request.name = dataObj.name;
        request.value = dataObj.value;
        // formに追加
        form.appendChild(request);
    }
    // form情報をbodyに代入
    document.body.appendChild(form);
    // submit処理
    form.submit();
}

/*====================================================================
    関数名： ShowDialog
    概　要： ダイアログメッセージ表示
    引　数： titleStr  ：タイトル情報
             bodyStr   ：内容情報(HTML形式)
             buttonsObj：ボタン情報(オブジェクト形式)
             optionObj ：その他オプション項目(オブジェクト形式)
*/
function ShowDialog ( titleStr = '', bodyStr = '', buttonsObj = {}, optionObj = {} ) {
    // ボタン情報がセットされていない場合
    if ( Object.keys( buttonsObj ).length == 0 ) {
        // 基本ボタン情報をセット
        buttonsObj = {
            text : '閉じる',
            click: function () { $(this).dialog('close'); }
        }
    }
    // オプション情報をセット
    var options = {};
    options['modal'] = true;
    options['title'] = titleStr;
    options['buttons'] = buttonsObj;
    if (optionObj['width']) options['width'] = optionObj['width'];
    if (optionObj['height']) options['height'] = optionObj['height'];
    if (optionObj['draggable'] != null) {
        options['draggable'] = optionObj['draggable'];
    }
    else if ( ! optionObj['draggable'] ) {
        options['draggable'] = false;
    }
    if (optionObj['close']) options['close'] = optionObj['close'];

    // 対象セレクターIDがセットされている場合
    if ( optionObj['id'] ) {
        var dialogDiv;
        // 対象セレクターが存在しない場合
        if ( ! $( '#' + optionObj['id'] ) ) {
            dialogDiv = $('<div />').attr( 'id', optionObj['id'] );
        }
        else {
            dialogDiv = $( '#' + optionObj['id'] );
        }
        if ( bodyStr != '' ) {
            // 内容情報がセットされている場合、代入
            dialogDiv.html( bodyStr );
        }
        // ダイアログ表示
        dialogDiv.dialog( options );
    }
    else {
        if ( bodyStr != '' ) {
            // DIV情報を変数に代入
            var dialogDiv = '<div>' + bodyStr + '</div>';
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
/*====================================================================
    関数名： ShowLording
    概　要： ローディング画面表示
*/
function ShowLording () {
    var lorderBgDiv = $( selLorderBg );
    var lorderDiv = $( selLorder );
    // ローディング用要素が総菜しない場合
    if (
        ! lorderBgDiv.length &&
        ! lorderDiv.length
    ) {
        // 各DIV要素を生成
        var lorderBgDiv = $('<div />').attr( 'id', idLorderBg );
        var lorderDiv = $('<div />')
                            .attr( 'id', idLorder )
                            .html('<img src="' + siteDir + 'images/loading.svg" />');
        // ローディング背景要素の最後に追加
        $( lorderBgDiv ).append( lorderDiv );
        // BODY要素の最後に追加
        $('body').append( lorderBgDiv );
    }
    $( lorderBgDiv ).fadeIn('normal');
    $( lorderDiv ).fadeIn('normal');
/*
    // 読込み表示処理
    $( careteBgDiv ).ready(function() {
        $( careteBgDiv ).show();
        $( careteDiv ).show();
    });
*/
}
/*====================================================================
    関数名： HiddenLording
    概　要： ローディング画面非表示
*/
function HiddenLording () {
    $( selLorderBg ).fadeOut('normal');
    $( selLorder ).fadeOut('normal');
}
/*====================================================================
    関数名： DisplayMsg
    概　要： 処理メッセージ表示
    引　数： titleStr：タイトル情報
             htmlStr ：要素内データ
             targetId：対象要素ID名
*/
function DisplayMsg ( titleStr, htmlStr, targetId = 'dialog_info' ) {
    // 対象DIV要素をセット
    var targetDiv = $('#' + targetId);
    // 対象DIV要素が存在しない場合
    if ( ! targetDiv.length ) {
        targetObj = {};
        targetObj['title'] = titleStr;
        targetObj['html'] = htmlStr;
        // 対象IDのDIV要素を追加
        AppendDiv ( targetId, targetObj );
    }
    
    // 対象要素が存在
    if ( targetDiv.length ) {
        // 対象要素に書込み
        targetDiv.html(htmlStr);
        // ダイアログ表示
        targetDiv.dialog({
            modal: true,
            buttons: {
                "閉じる": function() {
                    $(this).dialog("close");
                }
            }
        });
    }
}
/*====================================================================
    関数名： GetFormNameList
    概　要： FORM名一覧取得
    引　数： formId：FORM ID要素名
*/
function GetFormNameList ( formId = 'operation_form' ) {
    // 返値を初期化
    var returnVal = [];
    // FORMデータが存在の場合
    if ( $('#' + formId).length ) {
        // FORM情報一覧を配列形式で取得
        var form_param = $('#' + formId).serializeArray();
        for ( var i = 0, n = form_param.length; i < n; i ++ ) {
            for ( form_key in form_param[i] ) {
                // FORM名のみ
                if ( form_key == 'name' ) {
                    // 返値配列に追加
                    returnVal.push( form_param[i][form_key] );
                }
            }
        }
    }
    return returnVal;
}
/*====================================================================
    関数名： SetFormErrorMsg
    概　要： FORM送信時のエラーメッセージ表示位置調整
    引　数： formId：FORM ID要素名
*/
function SetFormErrorMsg ( formId = 'operation_form' ) {
    // FORM名一覧取得
    var formList = GetFormNameList ( formId );
    for ( var i = 0, n = formList.length; i < n; i ++ ) {
        // 対象FORMエラーIDのタグが存在する場合
        if ( $('#errors_' + formList[i]).length ) {
            var left = $('input[name="' + formList[i] + '"]').offset().left;
            var bottom = $('input[name="' + formList[i] + '"]').offset().top + $('input[name="' + formList[i] + '"]').outerHeight(true);
            // 表示位置を調整
            $('#errors_' + formList[i]).offset({ top: bottom, left: left });
        }
    }
}
/*====================================================================
    関数名： SetErrorMsg
    概　要： SetFormErrorMsgの単独版
    引　数： targetName：name要素名
*/
function SetErrorMsg ( targetName ) {
    // 対象FORMエラーIDのタグが存在する場合
    if ( $('#errors_' + targetName).length ) {
        var left = $('[name="' + targetName + '"]').offset().left;
        var bottom = $('[name="' + targetName + '"]').offset().top + $('[name="' + targetName + '"]').outerHeight(true);
        // 表示位置を調整
        $('#errors_' + targetName).offset({ top: bottom, left: left });
    }
}
/*====================================================================
    関数名： ClearErrorMsg
    概　要： セット中のエラーメッセージを削除
    引　数：
*/
function ClearErrorMsg () {
    // セット中のエラーメッセージ情報を前方一致で取得し削除
    $('[id^=' + ErrMsgLeadStr + ']').remove();
}
/*====================================================================
    関数名： AppendFormHiddenContents
    概　要： FORMにHIDDEN要素を追加
    引　数： keyStr：id, name要素名
             valStr：VALUE内容
             formId：FORM ID要素名
*/
function AppendFormHiddenContents ( keyStr, valStr = '', formId = 'operation_form' ) {
    // 対象ID要素が存在しない場合
    if ( document.getElementById( keyStr ) == null ) {
        // FORM要素最後にHIDDENタグを追加
        $("form#" + formId).append('<input type="hidden" id="' + keyStr + '" name="' + keyStr + '" value="' + valStr + '">');
    }
}
/*====================================================================
    関数名： AppendDiv
    概　要： DIV要素をBODY最後に追加
    引　数： targetId： id要素名
             targetObj: その他情報（オブジェクト形式）
*/
function AppendDiv( targetId, targetObj = {} ) {
/*
    // 対象ID要素が存在しない場合
    if ( document.getElementById( targetId ) == null ) {
        // div要素を作成し、IDを設定
        var el = document.createElement( 'div' );
        el.id = targetId;
        // BODY要素の最後に追加
        document.body.appendChild( el );
    }
*/
    // 対象DIV要素をセット
    var targetDiv = $('#' + targetId);
    // 対象DIV要素が存在しない場合
    if ( ! targetDiv.length ) {
        // DIV要素を生成
        var careteDiv = $('<div />').attr( 'id', targetId );

        // サンプル
//        targetObj['title'] = 'タイトルテスト';
//        targetObj['html'] = '内容テスト';

        for (key in targetObj) {
            if ( key == 'html' ) {
                careteDiv.html( targetObj[key] );
            }
            else {
                careteDiv.attr( key, targetObj[key] );
            }
        }
        // BODY要素の最後に追加
        $('body').append( careteDiv );
    }
}
/*====================================================================
    関数名： GetFormElemType
    概　要： Form要素のタイプを取得（エレメント名より）
    引　数： targetId： 対象エレメント名
*/
function GetFormElemType( targetName ) {
    // 返値を初期化
    var returnVal = false;
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
/*====================================================================
    関数名： SetFormElemVal
    概　要： Form要素の値をセット（エレメント名より）
    引　数： targetName： 対象エレメント名
*/
function SetFormElemVal( targetName, targetVal = '' ) {
    // タイプを取得
    var type = GetFormElemType( targetName );
    // text, select, textarea
    if (
        type == "text" ||
        type == "select" ||
        type == "textarea"
    ) {
        // 値をセット
        $('[name="' + targetName + '"]').val( targetVal );
    }
    // radio
    else if ( type == "radio" ) {
        // チェックを外す
        $('input[name="' + targetName + '"]').prop("checked", false);
        // チェックを入れる
        $('input[name="' + targetName + '"][value="' + targetVal + '"]').prop("checked", true);
    }
    // checkbox
    else if ( type == "checkbox" ) {
        // チェックを外す
        $('input[name="' + targetName + '"]').prop("checked", false);
        // 配列
        if ( Array.isArray (targetVal) ) {
            for ( var i = 0, n = targetVal.length; i < n; i ++ ) {
                // チェックを入れる
                $('input[name="' + targetName + '"][value="' + targetVal[i] + '"]').prop("checked", true);
            }
        }
        // 文字列
        else {
            // チェックを入れる
            $('input[name="' + targetName + '"][value="' + targetVal + '"]').prop("checked", true);
        }
    }
}