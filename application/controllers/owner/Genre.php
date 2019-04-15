<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Genre extends CI_Controller {
/*
    ■機　能： ジャンルページ画面処理
    ■概　要： 
    ■更新日： 2019/01/25
    ■担　当： crew.miwa
    ■更新履歴：
        2019/01/25: 作成開始
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
    }
    // 共通テンプレート
    function sharedTemplate ( $templateVal = "" )
    {
        // 各テンプレートをセット
        $templateVal['header_tpl'] = $this->load->view ( Login_model::AUTH_OWNER . '/header', $templateVal, true );
//        $templateVal['footer_tpl'] = $this->load->view ( 'footer', $templateVal, true );
        
        return $templateVal;
    }
    // TOP画面
    public function index()
    {
        // 認証確認処理
        $this->login_model->login_check_owner ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_OWNER . '/genre_model');
        
        $templateVal = $this->genre_model->ListTemplate();
        $this->load->view( Login_model::AUTH_OWNER . '/genre_list', $this->sharedTemplate ( $templateVal ));
    }
    // トピックス情報更新処理(AJAX)
    public function sort_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_OWNER . '/genre_model');
        
        // 更新処理
        $returnVal['result'] = $this->genre_model->EditSortAction ();
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // トピックス情報削除処理(AJAX)
    public function del_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_OWNER . '/genre_model');
        
        // 削除処理
        $returnVal = $this->genre_model->DelAction ();
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // 入力画面
    public function input()
    {
        // 認証確認処理
        $this->login_model->login_check_owner ();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_OWNER . '/genre_model');
        // サブミット情報の確認
        $submit_input_btn = $this->input->post_get( 'submit_input_btn', true );
        $submit_conf_btn = $this->input->post_get( 'submit_conf_btn', true );
        $submit_comp_btn = $this->input->post_get( 'submit_comp_btn', true );
        // FORM情報をセット
        $form = $this->genre_model->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $templateVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        // Submitボタンが押された場合
        if (
            $submit_conf_btn ||
            $submit_comp_btn
        )
        {
            // 共通エラー用設定
            $this->form_validation->set_error_delimiters('<div class="red">', '</div>');
            // エラーチェックルールをセット
            $config = $this->config_input_values();
            $this->form_validation->set_rules($config);
            // 入力画面（エラー時）
            if ($this->form_validation->run() == FALSE) {
                // テンプレート値を取得
                $templateVal = $this->genre_model->InputTemplate();
                // テンプレート読み込み
                $this->load->view ( Login_model::AUTH_OWNER . '/genre_input', self::sharedTemplate ( $templateVal ) );
            }
            else
            {
                // 確認ページ処理
                if ( ! $submit_comp_btn )
                {
                    // テンプレート値を取得
                    $templateVal = $this->genre_model->InputTemplate();
                    // テンプレート読み込み
                    $this->load->view ( Login_model::AUTH_OWNER . '/genre_conf', self::sharedTemplate ( $templateVal ) );
                }
                // 完了ページ処理
                else
                {
                    // 情報更新処理
                    $this->genre_model->EditNameAction ();
                    // テンプレート読み込み
                    $this->load->view ( Login_model::AUTH_OWNER . '/genre_comp', self::sharedTemplate () );
                }
            }
        }
        // 入力画面
        else
        {
            // テンプレート値を取得
            $templateVal = $this->genre_model->InputTemplate();
            // テンプレート読み込み
            $this->load->view ( Login_model::AUTH_OWNER . '/genre_input', self::sharedTemplate ( $templateVal ) );
        }
    }
    // バリデーション内容（入力）
    public function config_input_values ()
    {
        $returnVal[] = array(
            'field'   => 'name',
            'label'   => 'ジャンル名',
            'rules'   => 'required'
        );
        return ($returnVal);
    }
}
