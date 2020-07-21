<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
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
        $this->load->model( Base_lib::ADMIN_DIR . '/index_model');
   }
    // 共通テンプレート
    function sharedTemplate ($templateVal = "")
    {
        // ヘッダーテンプレートをセット
//        $headerVal['id'] = $this->login_model->get_id (Base_lib::ADMIN_DIR);

        return $templateVal;
    }
    // TOP画面
    public function index()
    {
/*
        // ログイン変数をセット
        $loginTarget['key'] = "admin";
        // ライブラリー読込み
        $this->load->library( 'login_lib', $loginTarget );

print $this->login_lib->GetAccount();
*/
        // FORM情報の確認
        $submit_btn = $this->input->post_get( 'submit_btn', true );
        $templateVal = $this->index_model->LoginTemplate ();
        
        // Submitボタンが押された場合
        if ( $submit_btn )
        {
            // エラーチェックルールをセット
            $config = $this->index_model->ConfigLoginValues();
            $this->index_model->LoginTemplate ( $this->form_validation->set_rules( $config ) );
            // エラー時
            if ($this->form_validation->run() == FALSE) {
                // テンプレート読み込み
                $this->load->view ( Base_lib::ADMIN_DIR . '/login', self::sharedTemplate ( $templateVal ) );
            }
            else
            {
                // 設定ページへ遷移
//                redirect( Base_lib::ADMIN_DIR . '/main' );
                // テンプレート読み込み
                $this->load->view ( Base_lib::ADMIN_DIR . '/main', self::sharedTemplate ( $templateVal ) );
            }
        }
        else
        {
            // テンプレート読み込み
            $this->load->view ( Base_lib::ADMIN_DIR . '/login', self::sharedTemplate ( $templateVal ) );
        }
    }
    // ログアウト処理
    public function logout()
    {
        // ログアウト処理
        $this->index_model->LogoutAction ();
        // テンプレート読み込み
        $this->load->view ( Base_lib::ADMIN_DIR . '/logout', self::sharedTemplate () );
    }
    // エラー処理
    public function error()
    {
print_r ($_SESSION);
        // モデル呼出し
        $this->load->model ( 'login_model' );
        // SESSION情報を削除
        $this->login_model->clear_values ( Base_lib::ADMIN_DIR );
        // テンプレート読み込み
        $this->load->view ( Base_lib::ADMIN_DIR . '/error', self::sharedTemplate () );
    }
}
