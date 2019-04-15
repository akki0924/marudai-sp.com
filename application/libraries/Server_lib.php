<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： サーバー用処理ライブラリー
    ■概　要： サーバー用の関数群
    ■更新日： 2018/01/19
    ■担　当： crew.miwa

    ■更新履歴：
     2018/01/19: 作成開始
     
    */

class Server_lib
{
    /*====================================================================
        関数名： SeparatorStr
        概　要： パスの区切り文字を取得
        引　数： 
    */
    public function SeparatorStr ()
    {
        
        // スペースを統一
        $keyword = str_replace ( '　', ' ', $keyword );
        // スペース毎に分割
        $keyword = explode ( ' ', $keyword );
        
        return $keyword;
    }
}
?>