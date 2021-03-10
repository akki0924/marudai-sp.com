<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Place extends MY_Controller {
/*
    ■機　能： 施設ページ画面処理
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
    // 共通テンプレート
    function sharedTemplate ( $templateVal = "" )
    {
        $templateVal = ( ! $templateVal ? array () : $templateVal );
        // 各テンプレートをセット
        $templateVal['header_tpl'] = $this->load->view ( Login_model::AUTH_ADMIN . '/header', $templateVal, true );
//        $templateVal['footer_tpl'] = $this->load->view ( 'footer', $templateVal, true );

        return $templateVal;
    }
    // TOP画面
    public function index()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');

        $templateVal = $this->place_model->ListTemplate();
        $this->load->view( Login_model::AUTH_ADMIN . '/place_list', $templateVal);
    }
    // トピックス情報更新処理(AJAX)
    public function sort_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');

        // 更新処理
        $returnVal['result'] = $this->place_model->EditSortAction ();

        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // トピックス情報削除処理(AJAX)
    public function del_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');

        // 削除処理
        $returnVal = $this->place_model->DelAction ();

        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // 入力画面
    public function input()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');
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
                $templateVal = $this->place_model->InputTemplate();
                // テンプレート読み込み
                $this->load->view ( Login_model::AUTH_ADMIN . '/place_input', $templateVal );
            }
            // 完了ページ処理
            else
            {
                // テンプレート値を取得
                $templateVal = $this->place_model->InputTemplate();
                // 情報更新処理
                $this->place_model->EditAction ();
                // 完了ページへ遷移
                redirect( Login_model::AUTH_ADMIN . '/place/comp' );
            }
        }
        // 入力画面
        else
        {
            // テンプレート値を取得
            $templateVal = $this->place_model->InputTemplate();
            // テンプレート読み込み
            $this->load->view ( Login_model::AUTH_ADMIN . '/place_input', $templateVal );
        }
    }
    // 完了画面
    public function comp()
    {
        // 認証確認処理
        $this->login_model->login_check_admin ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');

        // テンプレート値を取得
        $templateVal = $this->place_model->sharedTemplate();
        $this->load->view ( Login_model::AUTH_ADMIN . '/place_comp', $templateVal );
    }
    // アップロード画像の表示
    public function photo( $dir = "", $id = "" )
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/place_model');
        // 写真テンプレート書き出し処理
        $this->place_model->OutputPhotoTemplate ();
    }
    // バリデーション内容（入力）
    public function config_input_values ()
    {
        $returnVal[] = array(
            'field'   => 'account',
            'label'   => 'アカウント',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'password',
            'label'   => 'パスワード',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'name',
            'label'   => '名前',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'type_id',
            'label'   => 'タイプ',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'address',
            'label'   => '住所',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'lat',
            'label'   => 'Lat',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'lng',
            'label'   => 'Lng',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'closing',
            'label'   => '休館日',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'url',
            'label'   => 'URL',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'tel',
            'label'   => 'TEL',
            'rules'   => 'required'
        );
        return ($returnVal);
    }
}
