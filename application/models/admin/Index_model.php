<?php
/*
■機　能： インデックス用ライブラリ
■概　要： インデックス用関連全般
■更新日： 2019/01/30
■担　当： crew.miwa

■更新履歴：
 2019/01/30: 作成開始
*/

class Index_model extends CI_Model {
    // DBテーブル
    const MASTER_TABLE = "m_place";
    // ID生成用文字数
    const CREATE_ID_STRNUM = 10;
    /*====================================================================
        コントラクト
    */
/*
    public function __construct(){
        $this->load->database();
    }
*/
    /*====================================================================
        関数名： login_action
        概　要： ログイン処理
    */
    public function login_action ( $account, $password )
    {
        return $this->login_model->execute ( Login_model::AUTH_ADMIN, true, $account, $password );
    }
    /*====================================================================
        関数名： form_list
        概　要： フォーム用配列
    */
    public function form_list ()
    {
        $returnVal = array (
            'account',
            'password',
        );
        return $returnVal;
    }
}