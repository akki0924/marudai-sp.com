<?php
/*
■機　能： カレンダー用ライブラリ（オリジナル）
■概　要： カレンダー用関連全般
■更新日： 2019/12/04
■担　当： crew.miwa

■更新履歴：
 2019/12/04: 作成開始
*/
class Calendar_lib
{
    // 日付フォーマット形式
    const FORMAT_YMD = 'Y-m-d';
    // 曜日情報
    const FLG_WEEK_TYPE_N = false;   // 曜日の順番設定（true時、月曜スタート）
    const WEEK_FIRST_NUM = 0;       // 日曜
    const WEEK_LAST_NUM = 6;        // 土曜
    const WEEK_FIRST_N_NUM = 1;     // 月曜（ISO-8601形式）
    const WEEK_LAST_N_NUM = 7;      // 日曜（ISO-8601形式）
    // 月の日付
    const MONTH_CENTER_DAY = 15;    // 中日
    const MONTH_LAST_DAY = 31;      // 最終日
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名： GetCalendar
        概　要： カレンダーを生成する
    */
    public function GetCalendar ( $year = '', $month = '', $addMonth = false )
    {
        // 戻値を初期化
        $returnVal = array();
        // 対象年月を取得
        $year = ( $year != '' ? $year : date ('Y') );
        $month = ( $month != '' ? $month : date ('n') );
        // 月内の日数を取得
        $max_day = date( 'j', mktime( 0, 0, 0, $month + 1, 0, $year ) );
        // 曜日の順番設定がTRUEの場合
        $weekKey = $this->GetWeekKey ();
        $weekFirstNum = $this->GetWeekFirstNum ();
        $weekLastNum = $this->GetWeekLastNum ();
        // 初日と最終日の曜日番号を取得
        $firstWeek = date($weekKey, mktime(0, 0, 0, $month, 1, $year));
        $lastWeek = date($weekKey, mktime(0, 0, 0, $month, $max_day, $year));
        // 月前、月後設定がONの場合
        if ( $addMonth ) {
            // 月前情報
            for ( $w = $weekFirstNum; $w < $firstWeek; $w ++ ) {
                $sub_val = ($firstWeek - $w);
                $target_ymd = date(self::FORMAT_YMD, mktime(0, 0, 0, $month, (1 - $sub_val), $year));
                $returnVal[$target_ymd]['day'] = date('j', mktime(0, 0, 0, $month, (1 - $sub_val), $year));
                $returnVal[$target_ymd]['week'] = date($weekKey, mktime(0, 0, 0, $month, (1 - $sub_val), $year));
                $returnVal[$target_ymd]['this_month'] = false;
                $returnVal[$target_ymd]['week_first'] = ( $returnVal[$target_ymd]['week'] == $weekFirstNum ? true : false );
                $returnVal[$target_ymd]['week_last'] = ( $returnVal[$target_ymd]['week'] == $weekLastNum ? true : false );
            }
        }
        // 月内情報
        for ( $i = 1; $i <= $max_day; $i ++ ) {
            $target_ymd = date( self::FORMAT_YMD, mktime( 0, 0, 0, $month, $i, $year ) );
            $returnVal[$target_ymd]['day'] = date( 'j', mktime( 0, 0, 0, $month, $i, $year ) );
            $returnVal[$target_ymd]['week'] = date( $weekKey, mktime( 0, 0, 0, $month, $i, $year ) );
            $returnVal[$target_ymd]['this_month'] = true;
            $returnVal[$target_ymd]['week_first'] = ( $returnVal[$target_ymd]['week'] == $weekFirstNum ? true : false );
            $returnVal[$target_ymd]['week_last'] = ( $returnVal[$target_ymd]['week'] == $weekLastNum ? true : false );
        }
        // 月前、月後設定がONの場合
        if ( $addMonth ) {
            // 月後情報
            for ( $w = $weekLastNum, $i = 1; $w > $lastWeek; $w --, $i ++ ) {
                $target_ymd = date( self::FORMAT_YMD, mktime(0, 0, 0, $month, ($max_day + $i), $year) );
                $returnVal[$target_ymd]['day'] = date( 'j', mktime(0, 0, 0, $month, ($max_day + $i), $year) );
                $returnVal[$target_ymd]['week'] = date( $weekKey, mktime(0, 0, 0, $month, ($max_day + $i), $year) );
                $returnVal[$target_ymd]['this_month'] = false;
                $returnVal[$target_ymd]['week_first'] = ( $returnVal[$target_ymd]['week'] == $weekFirstNum ? true : false );
                $returnVal[$target_ymd]['week_last'] = ( $returnVal[$target_ymd]['week'] == $weekLastNum ? true : false );
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetYearBeforeMonth
        概　要： 対象前月の年情報を取得する
    */
    public function GetYearBeforeMonth ( $year, $month )
    {
        return date( 'Y', mktime(0, 0, 0, ( $month - 1 ), 1, $year) );
    }
    /*====================================================================
        関数名： GetMonthBeforeMonth
        概　要： 対象前月の月情報を取得する
    */
    public function GetMonthBeforeMonth ( $year, $month )
    {
        return date( 'n', mktime(0, 0, 0, ( $month - 1 ), 1, $year) );
    }
    /*====================================================================
        関数名： GetYearNextMonth
        概　要： 対象前月の年情報を取得する
    */
    public function GetYearNextMonth ( $year, $month )
    {
        return date( 'Y', mktime(0, 0, 0, ( $month + 1 ), 1, $year) );
    }
    /*====================================================================
        関数名： GetMonthNextMonth
        概　要： 対象前月の月情報を取得する
    */
    public function GetMonthNextMonth ( $year, $month )
    {
        return date( 'n', mktime(0, 0, 0, ( $month + 1 ), 1, $year) );
    }
    /*====================================================================
        関数名： GetMonthFirstDay
        概　要： 対象月の初日を取得する
    */
    public function GetMonthFirstDay ( $year, $month )
    {
        return date( 'j', mktime(0, 0, 0, $month, 1, $year) );
    }
    /*====================================================================
        関数名： GetMonthLastDay
        概　要： 対象月の最終日を取得する
    */
    public function GetMonthLastDay ( $year, $month )
    {
        return date( 'j', mktime(0, 0, 0, ( $month + 1 ), 0, $year) );
    }
    /*====================================================================
        関数名： GetWeekKey
        概　要： 週のキー情報を取得する
    */
    public function GetWeekKey ()
    {
        return ( self::FLG_WEEK_TYPE_N === false ? 'w' : 'N' );
    }
    /*====================================================================
        関数名： GetWeekFirstNum
        概　要： 週の最初のキー情報を取得する
    */
    public function GetWeekFirstNum ()
    {
        return ( self::FLG_WEEK_TYPE_N === false ? self::WEEK_FIRST_NUM : self::WEEK_FIRST_N_NUM );
    }
    /*====================================================================
        関数名： GetWeekLastNum
        概　要： 週の最後のキー情報を取得する
    */
    public function GetWeekLastNum ()
    {
        return ( self::FLG_WEEK_TYPE_N === false ? self::WEEK_LAST_NUM : self::WEEK_LAST_N_NUM );
    }
    /*====================================================================
        関数名： GetWeekList
        概　要： 週の最後のキー情報を取得する
    */
    public function GetWeekList ()
    {
        $returnVal = array ();
        if ( self::FLG_WEEK_TYPE_N === false ) {
            $returnVal[0] = '日';
            $returnVal[1] = '月';
            $returnVal[2] = '火';
            $returnVal[3] = '水';
            $returnVal[4] = '木';
            $returnVal[5] = '金';
            $returnVal[6] = '土';
        }
        else {
            $returnVal[1] = '月';
            $returnVal[2] = '火';
            $returnVal[3] = '水';
            $returnVal[4] = '木';
            $returnVal[5] = '金';
            $returnVal[6] = '土';
            $returnVal[7] = '日';
        }
        return $returnVal;
    }
}
