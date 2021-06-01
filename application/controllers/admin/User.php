<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 登録者管理ページ画面処理
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/05/31：新規作成
 */
class User extends MY_Controller
{
    /**
     * コントラクト
     */
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // モデル呼出し
        $this->load->model(Base_lib::ADMIN_DIR . '/user_model');
    }


    /**
     * 共通テンプレート
     *
     * @param array $templateVal：テンプレート用配列
     * @return array
     */
    // 共通テンプレート
    public function sharedTemplate(array $templateVal = array()) : ?array
    {
        // 各テンプレートをセット
        return $templateVal;
    }


    /**
     * 一覧画面
     *
     * @return void
     */
    public function index() : void
    {
        // FORM情報の確認
        $action = $this->input->post_get('action', true);
        // テンプレート情報をセット
        $templateVal = $this->user_model->ListTemplate();
        // テンプレート読み込み
        $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_list', $templateVal);
    }


    /**
     * 入力画面
     *
     * @return void
     */
    public function input()
    {
        // FORM情報の確認
        $action = $this->input->post_get('action', true);
        $id = $this->input->post_get('id', true);
        if (
            $action == 'conf' ||
            $action == 'back'
        ) {
            // エラーチェックルールをセット
            $config = $this->user_model->ConfigInputValues();
            $this->form_validation->set_rules($config);
            // バリデーション実行結果を取得
            $validFlg = $this->form_validation->run();
            $templateVal = $this->user_model->InputTemplate($validFlg);
            if (
                $validFlg &&
                $action == 'conf'
            ) {
                // 確認テンプレート読み込み
                $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_conf', $templateVal);
            } else {
                // 入力テンプレート読み込み
                $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_input', $templateVal);
            }
            // 完了画面処理
        } elseif ($action == 'comp') {
            // エラーチェックルールをセット
            $config = $this->user_model->ConfigInputValues();
            $this->form_validation->set_rules($config);
            // バリデーション実行結果を取得
            $validFlg = $this->form_validation->run();
            if ($validFlg) {
                // 登録処理
                $this->user_model->RegistAction($validFlg);
                // テンプレート読み込み
                redirect(Base_lib::ADMIN_DIR . '/user/comp' . ($id ? '/' . $id : ''));
            } else {
                $templateVal = $this->user_model->InputTemplate($validFlg);
                // 入力テンプレート読み込み
                $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_input', $templateVal);
            }
        } else {
            // テンプレート情報をセット
            $templateVal = $this->user_model->InputTemplate();
            // テンプレート読み込み
            $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_input', $templateVal);
        }
    }


    /**
     * 完了画面
     *
     * @return void
     */
    public function comp($id = "")
    {
        // テンプレート情報をセット
        $templateVal = $this->user_model->CompTemplate($id);
        // テンプレート読み込み
        $this->load->view(Base_lib::ADMIN_DIR . Base_lib::WEB_DIR_SEPARATOR . 'user_comp', $templateVal);
    }


    /**
     * 削除処理
     *
     * @return void
     */
    public function del()
    {
        // 削除処理
        $this->user_model->DelAction();
        // 一覧ページへ遷移
        redirect(Base_lib::ADMIN_DIR . '/user/');
    }
}