<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
    ■機　能： フォーム用ヘルパー関数処理群
    ■概　要： view用のフォーム用関数を定義
    ■更新日： 2017/06/13
    ■担　当： crew.miwa

    ■更新履歴：
     2017/06/13: 作成開始
*/
/*====================================================================
    関数名： SiteDir
    概　要： サイトディレクトリ情報取得
*/
function SiteDir($file_name = "")
{
    return (isset($file_name) ? D_ROOT . $file_name : D_ROOT);
}

/*====================================================================
    関数名 : VarDisp
    概　要 : 変数を表記可能に変換
    引　数 : $val : 対象文字列
             $escapeFlg : エスケープ処理 ( true : する, false : しない )
*/
function VarDisp($val = "", $escapeFlg = true)
{
    if ($escapeFlg === true) {
        return (isset($val) ? Base_lib::AddSlashes(html_escape($val)) : "");
    } else {
        return (isset($val) ? Base_lib::AddSlashes($val) : "");
    }
}
/*====================================================================
    関数名： VarRow
    概　要： 変数を列表記用に変換
    引　数 : $val : 対象文字列
             $escapeFlg : エスケープ処理 ( true : する, false : しない )
*/
function VarRow($val = "", $escapeFlg = true)
{
    if ($escapeFlg === true) {
        return (isset($val) ? nl2br(Base_lib::AddSlashes(html_escape($val))) : "");
    } else {
        return (isset($val) ? nl2br(Base_lib::AddSlashes($val)) : "");
    }
}
/*====================================================================
    関数名： VarNum
    概　要： 変数を数値表記用に変換
    引　数 : $val : 対象文字列
             $escapeFlg : エスケープ処理 ( true : する, false : しない )
*/
function VarNum($val = "", $escapeFlg = true)
{
    if ($escapeFlg === true) {
        return (isset($val) ? Base_lib::NumFormat(html_escape($val)) : "");
    } else {
        return (isset($val) ? Base_lib::NumFormat($val) : "");
    }
}

/*====================================================================
    関数名： JqueryFile
    概　要： 読込み用jQueryファイル名を取得
*/
function JqueryFile()
{
    return (Base_lib::JQUERY_FILE);
}
/*====================================================================
    関数名： JqueryUiJsFile
    概　要： 読込み用jQueryUI JSファイル名を取得
*/
function JqueryUiJsFile()
{
    return (Base_lib::JQUERY_UI_JS_FILE);
}
/*====================================================================
    関数名： JqueryUiCssFile
    概　要： 読込み用jQueryUI CSSファイル名を取得
*/
function JqueryUiCssFile()
{
    return (Base_lib::JQUERY_UI_CSS_FILE);
}
/*====================================================================
    関数名： NameSubmitBtn
    概　要： サブミットボタン名を取得
*/
function NameSubmitBtn()
{
    return (Base_lib::NAME_SUBMIT_BTN);
}
