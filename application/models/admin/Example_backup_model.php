<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 雛形データ用ライブラリー
 *
 * 雛形データの取得および処理する為の関数群 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/05/14：新規作成
 */
class Example_backup_model extends CI_Model
{
    /**
     * const
     */
    // ログイン対象
    const LOGIN_KEY = Base_lib::ADMIN_DIR;


    /**
     * コントラクト
     */
    public function __construct()
    {
        // 一覧テンプレート情報を取得
        $this->load->library('login_lib', array('key' => self::LOGIN_KEY));
    }


    /**
     * 共通テンプレート
     *
     * @param array|null $returnVal：各テンプレート用配列
     * @return array
     */
    public function sharedTemplate(?array $returnVal = array()) : array
    {
        // クラス定数をセット
        $returnVal['const'] = $this->GetBaseConstList();
        // ログ出力
        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog($_SERVER);
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog(validation_errors());

        return $returnVal;
    }


    /**
     * 一覧テンプレート情報を取得
     *
     * @param string $id：ID
     * @return array|null
     */
    public function 0Template(string $id = '') : ?array
    {

        return $this->sharedTemplate($returnVal);
    }










    /**
     * 一覧フォーム用配列
     *
     * @return array
     */
    public function FormDefaultList() : array
    {
        $returnVal = array(
            'page',
            'select_count',
            'search_keyword',
        );
        return $returnVal;
    }


    /**
     * 入力フォーム用配列
     *
     * @return array
     */
    public function FormInputList() : array
    {
        $returnVal = array(
            'id',
            'name',
        );
        return $returnVal;
    }


    /**
     * 入力ページ エラーチェック配列
     *
     * @return array
     */
    public function ConfigInputValues() : array
    {
        $returnVal = array(
            array(
                'field'   => 'id',
                'label'   => 'ID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'name',
                'label'   => '名前',
                'rules'   => 'required'
            ),
        );
        return ($returnVal);
    }


}
