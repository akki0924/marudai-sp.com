<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能： ステータス用処理ライブラリー
    ■概　要： ステータス用登録関数群
    ■更新日： 2020/01/29
    ■担　当： crew.miwa

    ■更新履歴：
     2020/01/29: 作成開始

    */

class Status_lib
{
    // 一般ステータス名
    const NAME_DEFAULT_ENABLE = '表示';
    const NAME_DEFAULT_DISABLE = '非表示';
    // 商品用ステータス名
    const NAME_ITEM_ENABLE = '公開';
    const NAME_ITEM_DISABLE = '非公開';
    // YES NO ステータス
    const NAME_YES_NO_ENABLE = 'YES';
    const NAME_YES_NO_DISABLE = 'NO';
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
        関数名 : GetSelectList
        概　要 : 対象ステータス一覧を取得
        引　数 : $key : 対象選択キー
    */
    function GetSelectList ( $key = 'DISPLAY' )
    {
        // 文字を大文字整形
        $key = strtoupper ( trim ( $key ) );
        
        $returnVal = array (
            'YES_NO' => array(
                Base_lib::STATUS_ENABLE =>  "YES",
                Base_lib::STATUS_DISABLE => "NO"
            ),
            'NO_YES' => array(
                Base_lib::STATUS_DISABLE => "YES",
                Base_lib::STATUS_ENABLE =>  "NO"
            ),
            'HAI' => array(
                Base_lib::STATUS_ENABLE =>  "はい",
            ),
            'POSSIBLE' => array(
                Base_lib::STATUS_ENABLE =>  "可",
                Base_lib::STATUS_DISABLE => "不可"
            ),
            'SURU_SHINAI' => array(
                Base_lib::STATUS_ENABLE =>  "する",
                Base_lib::STATUS_DISABLE => "しない"
            ),
            'SURU' => array(
                Base_lib::STATUS_ENABLE =>  "する",
            ),
            'SELECT_SHINAI_SURU' => array(
                Base_lib::STATUS_DISABLE => "選択しない",
                Base_lib::STATUS_ENABLE =>  "選択する"
            ),
            'SUMI' => array(
                Base_lib::STATUS_ENABLE =>  "済",
            ),
            'SUMI_MI' => array(
                Base_lib::STATUS_ENABLE =>  "済",
                Base_lib::STATUS_DISABLE => "未"
            ),
            'DISPLAY' => array(
                Base_lib::STATUS_ENABLE =>  "表示",
                Base_lib::STATUS_DISABLE => "非表示"
            ),
            'ARI_NASHI' => array(
                Base_lib::STATUS_ENABLE =>  "有",
                Base_lib::STATUS_DISABLE => "無"
            ),
            'HISSU' => array(
                Base_lib::STATUS_ENABLE =>  "必須",
            ),
            'ORDER_RESERVE' => array(
                Base_lib::STATUS_ENABLE =>  "保留解除する",
                Base_lib::STATUS_DISABLE => "キャンセル"
            ),
            'DOUI' => array(
                Base_lib::STATUS_ENABLE =>  "同意する",
            ),
            'PERMISSION' => array(
                Base_lib::STATUS_ENABLE =>  "許可",
                Base_lib::STATUS_DISABLE => "未許可"
            ),
            'KOUKAI_HIKOUKAI' => array(
                Base_lib::STATUS_ENABLE =>  "公開",
                Base_lib::STATUS_DISABLE => "非公開"
            ),
            'NEED' => array(
                Base_lib::STATUS_ENABLE =>  "必要",
                Base_lib::STATUS_DISABLE => "不要"
            ),
        );
        
        return $returnVal[ $key ];
    }
    /*====================================================================
        関数名 : GetSelectName
        概　要 : 対象ステータス名を取得
        引　数 : $key : 対象選択キー
                 $id : ステータスID
    */
    function GetSelectName ( $key = 'DISPLAY', $id )
    {
        // 一覧リストを取得
        $targetList = $this->GetSelectList ( $key );
        
        return $targetList[$id];
    }
    /*====================================================================
        関数名： SelectExists
        概　要： 対象ステータスが存在するかどうか
    */
    public function SelectExists( $key = 'DISPLAY', $status )
    {
        $targetList = $this->GetSelectList ( $key );
        
        return ( isset ( $targetList[ $status ] ) ? true : false );
    }
    /*====================================================================
        関数名 : GetDefaultList
        概　要 : 一般用ステータス一覧を取得
    */
    function GetDefaultList ()
    {
        $returnVal[Base_lib::STATUS_ENABLE] =   self::NAME_DEFAULT_ENABLE;
        $returnVal[Base_lib::STATUS_DISABLE] =  self::NAME_DEFAULT_DISABLE;

        return $returnVal;
    }
    /*====================================================================
        関数名 : GetDefaultName
        概　要 : 一般用ステータス名を取得
        引　数 : $id : ステータスID
    */
    function GetDefaultName ( $id )
    {
        // 一覧リストを取得
        $list = $this->GetDefaultList ();

        return $list[$id];
    }
    /*====================================================================
        関数名： DefaultExists
        概　要： 一般用ステータスが存在するかどうか
    */
    public function DefaultExists( $status )
    {
        $targetList = $this->GetDefaultList ();
        return ( isset  ( $targetList[ $status ] ) ? true : false );
    }
    /*====================================================================
        関数名 : GetItemList
        概　要 : 商品用ステータス一覧を取得
    */
    function GetItemList ()
    {
        $returnVal[Base_lib::STATUS_ENABLE] =   self::NAME_ITEM_ENABLE;
        $returnVal[Base_lib::STATUS_DISABLE] =  self::NAME_ITEM_DISABLE;

        return $returnVal;
    }
    /*====================================================================
        関数名 : GetItemName
        概　要 : 商品用ステータス名を取得
        引　数 : $id : ステータスID
    */
    function GetItemName ( $id )
    {
        // 一覧リストを取得
        $list = $this->GetItemList ();

        return $list[$id];
    }
    /*====================================================================
        関数名： ItemExists
        概　要： 商品用ステータスが存在するかどうか
    */
    public function ItemExists( $status )
    {
        $targetList = $this->GetItemList ();
        return ( isset  ( $targetList[ $status ] ) ? true : false );
    }
    /*====================================================================
        関数名 : GetYesNoList
        概　要 : YES NO用ステータス一覧を取得
    */
    function GetYesNoList ()
    {
        $returnVal[Base_lib::STATUS_ENABLE] =   self::NAME_YES_NO_ENABLE;
        $returnVal[Base_lib::STATUS_DISABLE] =  self::NAME_YES_NO_DISABLE;

        return $returnVal;
    }
    /*====================================================================
        関数名 : GetYesNoName
        概　要 : YES NO用ステータス名を取得
        引　数 : $id : ステータスID
    */
    function GetYesNoName ( $id )
    {
        // 一覧リストを取得
        $list = $this->GetYesNoList ();

        return $list[$id];
    }
    /*====================================================================
        関数名： YesNoExists
        概　要： YES NO用ステータスが存在するかどうか
    */
    public function YesNoExists( $status )
    {
        $targetList = $this->GetYesNoList ();
        return ( isset  ( $targetList[ $status ] ) ? true : false );
    }
}
