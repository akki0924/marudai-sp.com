<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {
/*
    ■機　能： スケジュールページ画面処理
    ■概　要：
    ■更新日： 2019/01/24
    ■担　当： crew.miwa
    ■更新履歴：
        2019/01/24: 作成開始
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
    }
    // TOP画面
    public function index()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');

        $templateVal = $this->schedule_model->ListTemplate();

        $this->load->view( Login_model::AUTH_ADMIN . '/schedule_list', $templateVal );
    }
    // トピックス情報更新処理(AJAX)
    public function sort_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');

        // 更新処理
        $returnVal['result'] = $this->schedule_model->EditSortAction ();

        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // トピックス情報削除処理(AJAX)
    public function del_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');

        // 削除処理
        $returnVal = $this->schedule_model->DelAction ();

        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // ステータス情報更新処理(AJAX)
    public function status_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');

        // 更新処理
        $returnVal['result'] = $this->schedule_model->EditStatusAction ();

        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // 入力画面
    public function input()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');
        // サブミット情報の確認
        $submit_conf_btn = $this->input->post_get( 'submit_conf_btn', true );
        // Submitボタンが押された場合
        if ( $submit_conf_btn )
        {
            // 共通エラー用設定
            $this->form_validation->set_error_delimiters('<div class="red">', '</div>');
            // エラーチェックルールをセット
            $config = $this->config_input_values();
            $this->form_validation->set_rules($config);
            // 入力画面（エラー時）
            if ($this->form_validation->run() == FALSE) {
                // テンプレート値を取得
                $templateVal = $this->schedule_model->InputTemplate();
                // テンプレート読み込み
                $this->load->view ( Login_model::AUTH_ADMIN . '/schedule_input', $templateVal );
            }
            else
            {
                // 情報更新処理
                $this->schedule_model->EditAction ();
                // 完了ページへ遷移
                redirect( Login_model::AUTH_ADMIN . '/schedule/comp' );
            }
        }
        // 入力画面
        else
        {
            // テンプレート値を取得
            $templateVal = $this->schedule_model->InputTemplate();
            // テンプレート読み込み
            $this->load->view ( Login_model::AUTH_ADMIN . '/schedule_input', $templateVal );
        }
    }
    // 完了画面
    public function comp()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/schedule_model');

        // テンプレート値を取得
        $templateVal = $this->schedule_model->sharedTemplate();
        $this->load->view ( Login_model::AUTH_ADMIN . '/schedule_comp', $templateVal );
    }
    // バリデーション内容（入力）
    public function config_input_values ()
    {
        $returnVal[] = array(
            'field'   => 'title',
            'label'   => 'タイトル',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'start',
            'label'   => '開始日',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'end',
            'label'   => '終了日',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'genre[]',
            'label'   => 'ジャンル',
            'rules'   => 'required'
        );
        return ($returnVal);
    }
}
