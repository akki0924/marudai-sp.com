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
 * @since 1.0.0     2021/04/26：新規作成
 */
class Example_model extends CI_Model
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
     * ログインページテンプレート
     *
     * @param boolean $validFlg
     * @return array|null
     */
    public function LoginTemplate(bool $validFlg = false) : ?array
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


    /**
     * フォーム用配列
     *
     * @return array
     */
    public function FormDefaultList() : array
    {
        $returnVal = array(
            'account',
            'password',
        );
        return $returnVal;
    }


    /**
     * エラーチェック配列
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
