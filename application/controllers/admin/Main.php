<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
/*
    ■機　能： Topページ画面処理
    ■概　要： 
    ■更新日： 2019/01/18
    ■担　当： crew.miwa
    ■更新履歴：
        2019/01/18: 作成開始
        2017/01/06: ログイン機能追加
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
        $this->load->model(Login_model::AUTH_ADMIN . '/main_model');
        
        // FORM情報をセット
        $form = $this->main_model->form_list ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $templateVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        
        $templateVal = $this->main_model->TopTemplate( $templateVal['form'] );

        $this->load->view( Login_model::AUTH_ADMIN . '/main', $this->sharedTemplate ( $templateVal ) );
    }
    // 刊行情報更新処理(AJAX)
    public function publication_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/main_model');
        
        // 検証
        $this->load->library( 'form_validation' );
        $config = $this->config_values();
        $this->form_validation->set_error_delimiters( '<div class="red">', '</div>' );
        $this->form_validation->set_rules( $config );
        // 成功時
        if ( $this->form_validation->run() == TRUE )
        {
            // 更新処理
            $returnVal = $this->main_model->EditPublicationAction();
            
            // 結果情報をセット
            $returnVal['result'] = true;
        }
        else 
        {
            // 結果情報をセット
            $returnVal['result'] = false;
        }
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // トピックス情報更新処理(AJAX)
    public function topics_sort_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/main_model');
        
        // 更新処理
        $returnVal['result'] = $this->main_model->EditTopicsSortAction ();
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // トピックス情報削除処理(AJAX)
    public function topics_del_part()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/main_model');
        
        // 削除処理
        $returnVal = $this->main_model->DelTopicsAction ();
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // Form値をセット
    function FormList () {
        $returnVal[] = 'site';
        $returnVal[] = 'manager';
        $returnVal[] = 'password';
        
        // submitボタンを追加
        $returnVal[] = 'submit_btn';
        return $returnVal;
    }
    // バリデーション内容
    public function config_values ()
    {
        $returnVal[] = array(
            'field'   => 'no',
            'label'   => 'No.',
            'rules'   => 'required|is_natural'
        );
        $returnVal[] = array(
            'field'   => 'start',
            'label'   => '日付範囲 開始日',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'end',
            'label'   => '日付範囲 終了日',
            'rules'   => 'required'
        );
        return ($returnVal);
    }
}
