<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能 : ページナビデータ作成プログラム
    ■概　要 : 取得数、表示数、ページ数に応じてページナビ用の処理群
    ■更新日 : 2020/01/27
    ■担　当 : crew.miwa
    ■更新履歴 ：
     2016/02/04 : 作成
     2020/01/27 : 表示件数用関数の追加
    */

class Pagenavi_lib
{
//=======================================
// 定数定義
    // ページナビ用
    const DEFAULT_PAGE = 1;         // 初期ページ
    const DEFAULT_LIST_COUNT = 50;  // 表示件数
    const DEFAULT_NAVI_COUNT = 5;   // ページナビ数
    
    /*====================================================================
        関数名 : GetList
        概　要 : ページデータ一覧を配列として返す
    */
    public static function GetValeus ( $maxLine, $page = "", $listCount = "" )
    {
        // 最大件数
        $returnVal['maxLine'] = $maxLine;
        // 現ページ情報をセット
        $returnVal['page'] = ( $page ? $page : self::DEFAULT_PAGE );
        // 表示数
        $listCount = ( $listCount ? $listCount : self::DEFAULT_LIST_COUNT );
        // ページナビ数
        $pageNaviCount = self::DEFAULT_NAVI_COUNT;
        
        // リストの最初のアイテム番号
        $returnVal['listStart'] = ( ( $returnVal['page'] - 1 ) * $listCount ) + 1;
        
        // 最大ページをセット
        if ( $listCount > 0 ) {
            $returnVal['maxPage'] = ceil ( $returnVal['maxLine'] / $listCount ) + ( ceil ( $returnVal['maxLine'] / $listCount ) > 0 ? 0 : 1 );
        }
        
        // ページナビ数が半分をセット
        $naviHalf = floor( $pageNaviCount / 2 );
        
        // ページジャンプ用の開始番号をセット
        $linkStartPage = $returnVal['page'] - $naviHalf;
        if ($linkStartPage <= 0) {
            // 1ページ目を下回る場合、ページ1を再セット
            $linkStartPage = self::DEFAULT_PAGE;
        }
        // ページジャンプ用の終了番号をセット
        $linkEndPage = $returnVal['page'] + $naviHalf;
        if ($linkEndPage >= $returnVal['maxPage']) {
            if ($linkStartPage > self::DEFAULT_PAGE) {
                // 最小ページを再セット
                $linkStartPage = $linkStartPage - ( $linkEndPage - $returnVal['maxPage'] );
                $linkStartPage = ( $linkStartPage > 0 ? $linkStartPage : self::DEFAULT_PAGE );
            }
            // 最大ページを上回る場合、最大ページを再セット
            $linkEndPage = $returnVal['maxPage'];
        }
        // ページ矢印をセット
        $returnVal['linkPrev'] = ( $returnVal['page'] > self::DEFAULT_PAGE ? ( $returnVal['page'] - 1 ) : false );
        $returnVal['linkNext'] = ( $returnVal['page'] < $returnVal['maxPage'] ? ( $returnVal['page'] + 1 ) : false );
        
        // ページ情報
        for ( $i = 0; $i < $pageNaviCount; $i ++ ) {
            $returnVal['link'][$i]['page'] = $linkStartPage + $i;
            $returnVal['link'][$i]['active'] = ( ( ( $linkStartPage + $i ) == $returnVal['page'] ) ? true : false );
            if ( ( $linkStartPage + $i ) >= $returnVal['maxPage'] ) {
                break;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： GetListCount
        概　要： 表示件数を取得
    */
    function GetListCount () {
        // 表示件数の一覧情報をセット
        $countList = array(
            self::DEFAULT_LIST_COUNT,
            100,
            200
        );
        // プルダウン用にセット
        foreach ( $countList AS $key ) {
            $returnValues[ $key ] = $key;
        }
        
        return $returnValues;
    }
}
?>