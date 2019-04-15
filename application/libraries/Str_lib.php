<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： 文字列用サポート処理ライブラリー
    ■概　要： 登録、更新、削除などの文字列登録関数群
    ■更新日： 2018/01/19
    ■担　当： crew.miwa

    ■更新履歴：
     2018/01/19: 作成開始
     
    */

class Str_lib
{
    /*====================================================================
        関数名： GetKeywordConvertVal
        概　要： キーワード文字列からスペース毎に分割した値を取得
        引　数： $keyword : キーワード
    */
    public function GetKeywordConvertVal ( $keyword )
    {
        // ホワイトスペース削除
        $keyword = trim ( $keyword );
        // スペースを統一
        $keyword = str_replace ( '　', ' ', $keyword );
        
        // スペースが存在する場合
        if( strpos ( $keyword, ' ' ) !== false ){
            // スペース毎に分割
            $returnVal = explode ( ' ', $keyword );
        }
        else
        {
            $returnVal[] = $keyword;
        }
        
        return $returnVal;
    }
}
?>