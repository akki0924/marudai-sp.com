<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
/*
    ■機　能： ログイン画面処理
    ■概　要： 
    ■更新日： 2018/02/05
    ■担　当： crew.miwa
    ■更新履歴：
        2016/12/28: 作成開始
        2017/01/06: ログイン機能追加
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/index_model');
        $this->load->model('login_model');
    }
    // 共通テンプレート
    function sharedTemplate ($templateVal = "")
    {
/*
        $templateVal['header_tpl'] = $this->load->view ( Login_model::AUTH_ADMIN . '/header', $templateVal, true );
        $templateVal['footer_tpl'] = $this->load->view ( Login_model::AUTH_ADMIN . '/footer', $templateVal, true );
*/
        // ヘッダーテンプレートをセット
//        $headerVal['id'] = $this->login_model->get_id (Login_model::AUTH_ADMIN);
        
        return $templateVal;
    }
    // TOP画面
    public function index()
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/index_model');
        // form情報の確認
        $submit_btn = $this->input->post_get( 'submit_btn', true );
        // FORM情報をセット
        $form = $this->index_model->form_list ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $templateVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        // Submitボタンが押された場合
        if ($submit_btn)
        {
            // 共通エラー用設定
            $this->form_validation->set_error_delimiters('<div class="red">', '</div>');
            // エラーチェックルールをセット
            $config = $this->config_values();
            $this->form_validation->set_rules($config);
            // エラー時
            if ($this->form_validation->run() == FALSE) {
                // テンプレート読み込み
                $this->load->view ( Login_model::AUTH_ADMIN . '/login', self::sharedTemplate ( $templateVal ) );
            }
            else
            {
                // 設定ページへ遷移
                redirect( Login_model::AUTH_ADMIN . '/main' );
            }
        }
        else
        {
            // テンプレート読み込み
            $this->load->view ( Login_model::AUTH_ADMIN . '/login', self::sharedTemplate ( $templateVal ) );
        }
    }
    // ログアウト処理
    public function logout()
    {
        // モデル呼出し
        $this->load->model ( 'login_model' );
        // SESSION情報を削除
        $this->login_model->clear_values ( login_model::AUTH_ADMIN );
        // テンプレート読み込み
        $this->load->view ( Login_model::AUTH_ADMIN . '/error', self::sharedTemplate () );
    }
    // エラー処理
    public function error()
    {
print_r ($_SESSION);
        // モデル呼出し
        $this->load->model ( 'login_model' );
        // SESSION情報を削除
        $this->login_model->clear_values ( login_model::AUTH_ADMIN );
        // テンプレート読み込み
        $this->load->view ( Login_model::AUTH_ADMIN . '/error', self::sharedTemplate () );
    }
    /*====================================================================
        関数名： login_values
        概　要： エラーチェック配列
    */
    public function config_values () {
        $returnValues = array(
            array(
                'field'   => 'account',
                'label'   => 'ユーザーID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'パスワード',
                'rules'   => 'required|callback__check_admin_login[' . $this->input->post_get ( "account", true ) . ']'
            ),
        );
        return ($returnValues);
    }
    public function _check_admin_login ( $password, $account )
    {
        // モデル呼出し
        $this->load->model(Login_model::AUTH_ADMIN . '/index_model');
        // 検証ライブラリ呼出し
        $this->load->library( 'form_validation' );
        
        // ログイン可能かどうか確認
        if ( $this->index_model->login_action ( $account, $password ) )
        {
            // バリデーションOK
            return TRUE;
        }
        // DBに登録済の為バリデーションNG
        $this->form_validation->set_message( '_check_admin_login', '登録情報が確認できません。入力内容をご確認ください' );
        return FALSE;
    }
}
