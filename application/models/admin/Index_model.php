<?php
/*
■機　能： 管理画面トップページ用ライブラリ
■概　要： 
■更新日： 2019/12/24
■担　当： crew.miwa

■更新履歴：
 2019/12/24: 作成開始
*/

class Index_model extends CI_Model {
    // ログイン対象
    const LOGIN_KEY = "admin";
    /*====================================================================
        コントラクト
    */
    public function __construct(){
        $loginTarget['key'] = self::LOGIN_KEY;
        // ライブラリー読込み
        $this->load->library( 'login_lib', $loginTarget );
    }
    /*====================================================================
        関数名： sharedTemplate
        概　要： 共通テンプレート情報を取得
    */
    function sharedTemplate ( $returnVal = "" )
    {
        // 変数を再セット
        $returnVal = ( $returnVal != "" ? $returnVal : array () );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： LoginTemplate
        概　要： ログインページテンプレート情報を取得
    */
    public function LoginTemplate ( $validation_flg = false )
    {
        // 一覧情報をセット
        $returnVal = array ();
        // FORM情報をセット
        $form['account'] = $this->input->post_get('account', true);         // アカウント
        $form['password'] = $this->input->post_get('password', true);       // パスワード
        
        // FORM情報を返り値用配列にセット
        $returnVal['form'] = $form;
        
        return $this->sharedTemplate ( $returnVal );
    }
    /*====================================================================
        関数名： LogoutAction
        概　要： ログアウト処理
    */
    public function LogoutAction ()
    {
        // 対象SESSION情報を削除
        $this->login_lib->ClearSessionValues ();
    }
    /*====================================================================
        関数名： ConfigLoginValues
        概　要： ログインページ エラーチェック配列
    */
    public function ConfigLoginValues()
    {
        $returnValues = array(
            array(
                'field'   => 'account',
                'label'   => 'アカウント',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'パスワード',
                'rules'   => 'required|LoginAdminCheck[' . $this->input->post_get ( 'account', true ) . ']'
            ),
        );
        return ($returnValues);
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