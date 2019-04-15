<?php
/*
■機　能： 管理用ライブラリ
■概　要： 管理用関連全般
■更新日： 2019/01/15
■担　当： crew.miwa

■更新履歴：
 2019/01/15: 作成開始
*/

class Admin_model extends CI_Model {
    // ID生成用文字数
//    const CREATE_ID_STRNUM = 10;
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
        関数名： login_form_list
        概　要： フォーム用配列
    */
    public function login_form_list ()
    {
        $returnVal = array (
            'account',
            'password',
        );
        return $returnVal;
    }
    /*====================================================================
        関数名： login_values
        概　要： エラーチェック配列
    */
    public function config_values () {
        $returnValues = array(
            array(
                'field'   => 'account',
                'label'   => 'ユーザーID',
                'rules'   => 'required'
            ),
/*
            array(
                'field'   => 'password',
                'label'   => 'パスワード',
                'rules'   => 'required'
            ),
*/
            array(
                'field'   => 'password',
                'label'   => 'パスワード',
                'rules'   => 'required|admin_login[' . $this->input->post_get ( "account", true ) . ']'
            ),
/*
            array(
                'field'   => 'login',
                'label'   => 'ログイン',
                'rules'   => 'admin_login[' . $this->input->post_get ( "account", true ) . ',' . $this->input->post_get ( "password", true ) . ']'
            ),
*/
        );
        return ($returnValues);
    }
}