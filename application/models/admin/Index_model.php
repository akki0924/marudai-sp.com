<?php
/**
 * 管理画面ログイン画面用モデル
 *
 * ログイン画面、およびログイン、ログアウト処理
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.1
 * @since 1.0.0     2019/12/24：新規作成
 * @since 1.0.1     2021/04/25：コメントをPHPDoc版に変更
 */
class Index_model extends CI_Model
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
        $loginTarget['key'] = self::LOGIN_KEY;
        // ライブラリー読込み
        $this->load->library('login_lib', $loginTarget);
    }


    /**
     * 共通テンプレート
     *
     * @param array|null $returnVal
     * @return array
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


    /**
     * ログイン画面テンプレート
     *
     * @param boolean $validFlg
     * @return array|null
     */
    public function LoginTemplate(bool $validFlg = false) : ?array
    {
        // 一覧情報をセット
        $returnVal = array();
        // FORM情報をセット
        foreach ($this->FormLoginList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        return $this->sharedTemplate($returnVal);
    }


    /**
     * ログイン処理
     *
     * @return boolean
     */
    public function LoginAction() : bool
    {
        // 一覧情報をセット
        $returnVal = false;
        // FORM情報をセット
        foreach ($this->FormLoginList() as $key) {
            $form[$key] = $this->input->post_get($key, true);
        }
        // ログイン処理（SESSION情報を登録）
        $returnVal = $this->login_lib->LoginAction($form['account'], $form['password']);

        return $returnVal;
    }


    /**
     * ログアウト処理
     *
     * @return void
     */
    public function LogoutAction() : void
    {
        // 対象SESSION情報を削除
        $this->login_lib->ClearSessionValues();
    }


    /**
     * ログインフォーム用配列
     *
     * @return array
     */
    public function FormLoginList() : array
    {
        $returnVal = array(
            'account',
            'password',
        );
        return $returnVal;
    }


    /**
     * ログイン画面 エラーチェック配列
     *
     * @return array
     */
    public function ConfigLoginValues() : array
    {
        $returnVal = array(
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
        return ($returnVal);
    }
}
