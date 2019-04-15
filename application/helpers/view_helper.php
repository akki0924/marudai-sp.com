<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    ■機　能： フォーム用ヘルパー関数処理群
    ■概　要： view用のフォーム用関数を定義
    ■更新日： 2017/06/13
    ■担　当： crew.miwa
    
    ■更新履歴：
     2017/06/13: 作成開始
*/
/*====================================================================
    関数名： site_dir
    概　要： 変数を表記可能に変換
*/
function site_dir ( $file_name = "" )
{
    return ( isset ( $file_name ) ? D_ROOT . $file_name : D_ROOT );
}

/*====================================================================
    関数名： var_disp
    概　要： 変数を表記可能に変換
*/
function var_disp ( $val = "" )
{
    return ( isset ( $val ) ? Base::add_slashes ( $val ) : "" );
}
?>