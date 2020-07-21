<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能 : 日付用処理ライブラリー
    ■概　要 : 日付用登録関数群
    ■更新日 : 2020/03/02
    ■担　当 : crew.miwa

    ■更新履歴：
     2020/03/02: 作成開始

    */

class Date_lib
{
    const DEFAULT_FUTURE_YEAR = 2;          // 現在の年から先の年数
    
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
         関数名 : GetYearList
         概　要 : 年一覧を取得
    */
    function GetYearList ( $unitName = '' , $startYear = "", $futureYear = "" ) {
        
        $startYear = ( $startYear !== "" ? $startYear : ( date ('Y') - 1 ) );
        $endYear = date( 'Y' ) + ( $futureYear !== "" ? $futureYear : self::DEFAULT_FUTURE_YEAR );

        // リストを作成
        $values = array ();
        for ( $i = $startYear, $value = $startYear; $i <= $endYear; $i ++ ) {
            $returnVal[ $i ] = $i . $unitName;
        }
        
        return $returnVal;
    }
    
    /*====================================================================
         関数名 : GetMonthList
         概　要 : 月一覧を取得
         引　数 : $unitName : 単位
    */
    public function GetMonthList ( $unitName = '' ) {
        $monthStart = 1;
        $monthEnd = 12;
        
        for ( $i = $monthStart; $i <= $monthEnd; $i ++ ) {
            $returnVal[ $i ] = $i . $unitName;
        }
        
        return $returnVal;
    }
    /*====================================================================
         関数名 : GetDayList
         概　要 : 日一覧を取得
         引　数 : $unitName : 単位
    */
    public function GetDayList ( $unitName = '' ) {
        $dayStart = 1;
        $dayEnd = 31;
        
        for ( $i = $dayStart; $i <= $dayEnd; $i ++ ) {
            $returnVal[ $i ] = $i . $unitName;
        }
        
        return $returnVal;
    }
}
