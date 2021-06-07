<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 管理画面ログイン画面処理
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.3
 * @since 1.0.0     2016/12/28：新規作成
 * @since 1.0.1     2017/01/06：ログイン機能追加
 * @since 1.0.2     2021/04/02：actionの値で動作するよう仕様変更
 * @since 1.0.3     2021/05/31：自動生成用に修正
 */
class Index extends MY_Controller
{
    /**
     * コントラクト
     */
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // モデル呼出し
        $this->load->model(Base_lib::ADMIN_DIR . '/index_model');
    }


    /**
     * ログイン画面および、ログイン処理
     *
     * @return void
     */
    public function index()
    {
        $action = $this->input->post_get('action', true);

        // Submitボタンが押された場合
        if ($action == 'login') {
            // エラーチェックルールをセット
            $config = $this->index_model->ConfigLoginValues();
            $this->form_validation->set_rules($config);
            // エラー時
            if ($this->form_validation->run() == false) {
                $templateVal = $this->index_model->LoginTemplate();
                // テンプレート読み込み
                $this->load->view(Base_lib::ADMIN_DIR . '/login', $templateVal);
            } else {
                $this->index_model->LoginAction();
                // 設定ページへ遷移
                redirect(Base_lib::ACCESS_ADMIN_DIR . '/sheet1');
            }
        } else {
            $templateVal = $this->index_model->LoginTemplate();
            // テンプレート読み込み
            $this->load->view(Base_lib::ADMIN_DIR . '/login', $templateVal);
        }
    }


    /**
     * ログアウト処理
     *
     * @return void
     */
    public function logout()
    {
        // ログアウト処理
        $this->index_model->LogoutAction();
        // ログインページへ遷移
        redirect(Base_lib::ACCESS_ADMIN_DIR);
    }


    /**
     * エラー処理
     *
     * @return void
     */
    public function error()
    {
        // モデル呼出し
        $this->load->model('login_model');
        // SESSION情報を削除
        $this->login_model->clear_values(Base_lib::ADMIN_DIR);
        // ログインページへ遷移
        redirect(Base_lib::ACCESS_ADMIN_DIR);
    }
}
