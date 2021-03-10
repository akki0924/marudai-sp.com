<?php
/*
■機　能： Javascript及びCss用モデル
■概　要： Javascript及びCss用関連全般
■更新日： 2021/02/26
■担　当： crew.miwa

■更新履歴：
 2021/02/26: 作成開始
*/
class Jscss_model extends CI_Model
{
    /**
     * コントラクト
     */
    public function __construct()
    {
    }
    /**
     * 共通テンプレート
     *
     * @param array|null $returnVal
     * @return array|null
     */
    public function sharedTemplate(?array $returnVal = array()) : ?array
    {
        // 変数を再セット
        $returnVal = ($returnVal != "" ? $returnVal : array());
        // クラス定数をセット
        $returnVal['const'] = $this->gmaps_lib->GetConstList('gmaps_lib');

        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog(validation_errors());
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog($_POST);
        Base_lib::ConsoleLog($_FILES);

        return $returnVal;
    }
    /*====================================================================
        関数名： GetConstList
        概　要： クラス定数一覧を取得
        戻り値： クラス定数一覧
    */
    public static function GetConstList(): array
    {
        // 返値を初期化
        $returnVal = array();
        // 定数取得用クラス宣言
        $targetClass = new ReflectionClass(__CLASS__);
        // 定数一覧を配列で取得
        $tempVal = $targetClass->getConstants();
        foreach ($tempVal as $key => $val) {
            // 配列キーを小文字に変換
            $returnVal[mb_strtolower($key)] = $val;
        }
        return $returnVal;
    }
}
