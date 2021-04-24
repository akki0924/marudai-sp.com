<?php
/*
■機　能： 管理画面トップページ用ライブラリ
■概　要：
■更新日： 2019/12/24
■担　当： crew.miwa

■更新履歴：
 2019/12/24: 作成開始
*/

class Index_model extends CI_Model
{
    // ログイン対象
    const LOGIN_KEY = "admin";
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        $loginTarget['key'] = self::LOGIN_KEY;
        // ライブラリー読込み
        $this->load->library('login_lib', $loginTarget);
    }
    /*====================================================================
        関数名： sharedTemplate
        概　要： 共通テンプレート情報を取得
    */
    public function sharedTemplate($returnVal = array())
    {
        // クラス定数をセット
        $returnVal['const'] = $this->base_lib->GetBaseConstList();

        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog($_SERVER);
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog(validation_errors());

        return $returnVal;
    }
    /*====================================================================
        関数名： LoginTemplate
        概　要： ログインページテンプレート情報を取得
    */
    public function LoginTemplate($validation_flg = false)
    {
        // 一覧情報をセット
        $returnVal = array();
        // FORM情報をセット
        foreach ($this->FormDefaultList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }

        return $this->sharedTemplate($returnVal);
    }
    /*====================================================================
        関数名： LoginAction
        概　要： ログインページテンプレート情報を取得
    */
    public function LoginAction()
    {
        // 一覧情報をセット
        $returnVal = false;
        // FORM情報をセット
        foreach ($this->FormDefaultList() as $key) {
            $form[$key] = $this->input->post_get($key, true);
        }
        // ログイン処理（SESSION情報を登録）
        $returnVal = $this->login_lib->LoginAction($form['account'], $form['password']);

        return $returnVal;
    }
    /*====================================================================
        関数名： LogoutAction
        概　要： ログアウト処理
    */
    public function LogoutAction()
    {
        // 対象SESSION情報を削除
        $this->login_lib->ClearSessionValues();
    }
    /*====================================================================
        関数名： FormDefaultList
        概　要： フォーム用配列
    */
    public function FormDefaultList()
    {
        $returnVal = array(
            'account',
            'password',
        );
        return $returnVal;
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
                'rules'   => 'required|ValidLoginAdmin[' . $this->input->post_get('account', true) . ']'
            ),
        );
        return ($returnValues);
    }
}
