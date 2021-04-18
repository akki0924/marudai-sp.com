<?php
/*
■機　能： 静的ページ情報用モデル
■概　要： 静的ページ情報用関連全般
■更新日： 2021/01/12
■担　当： crew.miwa

■更新履歴：
 2021/01/12：作成開始
*/

class Static_model extends CI_Model
{
    // 定数
    const LOGIN_KEY = 'user';   // 対象ログインキー
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        $loginTarget['key'] = self::LOGIN_KEY;
        // ライブラリー読込み
        $this->load->library('login_lib', $loginTarget);
        // ログイン情報の確認
        $this->login_lib->LoginCheck();
    }
    /*====================================================================
        関数名： sharedTemplate
        概　要： 共通テンプレート情報を取得
    */
    public function sharedTemplate($returnVal = "")
    {
        // 変数を再セット
        $returnVal = ($returnVal != "" ? $returnVal : array());
        // ライブラリー読込み
        $loginTarget['key'] = self::LOGIN_KEY;
        $this->load->library('login_lib', $loginTarget);
        // ログインID
        $returnVal['login_id'] = $this->login_lib->GetSessionId('id');
        // ログイン名
        if ($returnVal['login_id']) {
            // ユーザー情報ライブラリの読込み
            $this->load->library(Base_lib::MASTER_DIR . '/user_lib');
            //
            $returnVal['login_name'] = $this->user_lib->GetName($returnVal['login_id'], true);
        } else {
            $returnVal['login_name'] = '';
        }

        return $returnVal;
    }
    /*====================================================================
        関数名： DefaultTemplate
        概　要： TOPページテンプレート情報を取得
    */
    public function DefaultTemplate()
    {
        // 戻り値を初期化
        $returnVal = array();

        return $this->sharedTemplate($returnVal);
    }
}
