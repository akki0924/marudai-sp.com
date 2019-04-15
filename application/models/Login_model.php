<?php
/*
■機　能： ログイン管理用ライブラリ
■概　要： ログイン管理全般
■更新日： 2016/12/27
■担　当： crew.miwa

■更新履歴：
 2016/12/27: 作成開始
*/

class Login_model extends CI_Model {
    
    // DBテーブル
    const TABLE_OWNER = Place_lib::MASTER_TABLE;
    const TABLE_ADMIN = Admin_lib::MASTER_TABLE;
    // 各form名
    const ACCOUNT_NAME = "account";
    const PASSWORD_NAME = "password";
    // auth
    const AUTH_OWNER = "owner";
    const AUTH_ADMIN = "admin";
    // 初期値
    const DEFAULT_ADMIN_ID = "uSraUth1";
    /*====================================================================
        コントラクト
    */
    public function __construct(){
        // ライブラリー読込み
        $this->load->library ( 'session' );
        $this->load->library ( 'place_lib' );
    }
    /*====================================================================
        関数名： execute
        概　要： ログイン認証処理
    */
    public function execute ( $auth = "", $public = false, $account = "", $password = "" )
    {
        // 認証対象
        $auth = ( $auth != "" ? $auth : self::AUTH_OWNER );
        // 返り値の初期値
        $returnVal = false;
        
        // 認証済
        if ($this->session->has_userdata ( $auth . "_" . "id" ) && $this->session->has_userdata ( $auth . "_" . "success" ) )
        {
            // SQLクエリ
            $query = $this->db->query("
                SELECT COUNT(*) AS count FROM " . self::target_table ( $auth ) . "
                WHERE (
                    id = " . Base::empty_to_null( self::get_id ( $auth ) ) . "
                    " . ( $public ? " AND status >= " . Base::STATUS_ENABLE : "" ) . "
                )
            ");
            // 結果が、空でない場合
            if ( $query->num_rows() > 0 )
            {
                foreach ( $query->result() as $row )
                {
                    if ( $row->count > 0 )
                    {
                        $returnVal = true;
                    }
                    else
                    {
                        // SESSION情報クリア
                        self::clear_values ( $auth );
                    }
                }
            }
            else
            {
                // SESSION情報クリア
                self::clear_values ( $auth );
            }
        }
        // 未認証
        else
        {
            // 一般ユーザーの場合
            if ( $auth == self::AUTH_OWNER )
            {
                // form情報の取得
                $password = ( $password ? $password : $this->input->post_get ( 'password', true ) );
                // SQLクエリ
                $query = $this->db->query("
                    SELECT id FROM " . self::target_table ( $auth ) . "
                    WHERE (
                        account = '" . Base::add_slashes ( $account ) . "' AND
                        password = '" . Base::add_slashes ( $password ) . "'
                        " . ( $public ? " AND status >= " . Base::STATUS_ENABLE : "" ) . "
                    )
                ");
            }
            // 管理ユーザーの場合
            else
            {
                // form情報の取得
                $account = ( $account ? $account : $this->input->post_get ( 'account', true ) );
                $password = ( $password ? $password : $this->input->post_get ( 'password', true ) );
                // SQLクエリ
                $query = $this->db->query("
                    SELECT id FROM " . self::target_table ( $auth ) . "
                    WHERE (
                        account = '" . Base::add_slashes ( $account ) . "' AND
                        password = '" . Base::add_slashes ( $password ) . "'
                        " . ( $public ? " AND status >= " . Base::STATUS_ENABLE : "" ) . "
                    )
                ");
            }
            // 結果が、空でない場合
            if ( $query->num_rows() > 0 )
            {
                foreach ( $query->result() as $row )
                {
                    // SESSION登録処理
                    self::set_values ( $row->id, $auth );
                    $returnVal = true;
                }
            }
            else
            {
                self::clear_values ( $auth );
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： set_values
        概　要： SESSION情報を登録
    */
    public function set_values ( $id, $auth )
    {
        $values = array (
            $auth . "_" . "id" => $id,
            $auth . "_" . "last_login" => date ( "Y-m-d H:i:s" ),
            $auth . "_" . "success" => true
        );
        return $this->session->set_userdata ( $values );
    }
    /*====================================================================
        関数名： get_values
        概　要： SESSION情報を取得
    */
    public function get_values ( $auth )
    {
        $values = array (
            $auth . "_" . "id" => $this->session->userdata ( $auth . "_" . "id" ),
            $auth . "_" . "last_login" => $this->session->userdata ( $auth . "_" . "last_login" ),
            $auth . "_" . "success" => $this->session->userdata ( $auth . "_" . "success" )
        );
        return $values;
    }
    /*====================================================================
        関数名： get_id
        概　要： SESSION情報を取得
    */
    public function get_id ( $auth )
    {
        return $this->session->userdata ( $auth . "_" . "id" );
    }
    /*====================================================================
        関数名： clear_values
        概　要： SESSION情報を削除
    */
    public function clear_values ( $auth )
    {
        // 表画面の場合
        if ( $auth == self::AUTH_OWNER )
        {
            // 選択店舗情報を削除
            self::clear_select_admin_id ();
        }
        $values = array (
            $auth . "_" . "id",
            $auth . "_" . "last_login",
            $auth . "_" . "success",
        );
        return $this->session->unset_userdata ( $values );
    }
    /*====================================================================
        関数名： login_action
        概　要： ログイン情報（SESSION）実行処理
    */
    public function login_action ( $auth, $account, $passowrd )
    {
        if ( $auth == self::AUTH_OWNER )
        {
            // 認証確認処理
            if ( $this->login_model->execute ( $auth, true, $account, $passowrd ) )
            {
                // ページ遷移
                redirect('top');
            }
            else {
                // エラーメッセージ
                $templateVal['error_msg'] = "パスワードが違います";
                // テンプレート読み込み
                $this->load->view('login', self::sharedTemplate($templateVal));
            }
        }
        else if ( $auth == self::AUTH_ADMIN )
        {
            // 認証確認処理
            if ( $this->login_model->execute ( $auth, true, $account, $passowrd ) )
            {
                // ページへ遷移
                redirect( Login_model::AUTH_ADMIN . '/config' );
            }
            else {
                // エラーメッセージ
                $templateVal['error_msg'] = "入力された情報は登録されておりません。";
                // テンプレート読み込み
                $this->load->view ( Login_model::AUTH_ADMIN . '/login', self::sharedTemplate ( $templateVal ) );
            }
        }
    }
    /*====================================================================
        関数名： login_check
        概　要： ログイン情報（SESSION）の確認処理
    */
    public function login_check ( $auth )
    {
        if (
            $auth == self::AUTH_OWNER ||
            $auth == self::AUTH_ADMIN
        )
        {
            // 認証確認処理
            if ( !$this->login_model->execute ( $auth, true ) )
            {
                if ( $auth == self::AUTH_OWNER )
                {
                    // ページ遷移
                    redirect( self::AUTH_OWNER . '/index/error' );
                }
                else
                {
                    // ページ遷移
                    redirect( self::AUTH_ADMIN . '/index/error' );
                }
            }
        }
    }
    /*====================================================================
        関数名： login_check_admin
        概　要： ログイン情報（SESSION）の確認処理
    */
    public function login_check_admin ()
    {
        $this->login_model->login_check ( self::AUTH_ADMIN );
    }
    /*====================================================================
        関数名： login_check_owner
        概　要： ログイン情報（SESSION）の確認処理
    */
    public function login_check_owner ()
    {
        $this->login_model->login_check ( self::AUTH_OWNER );
    }
    /*====================================================================
        関数名： target_table
        概　要： SESSION情報対象のTABLE名を取得
    */
    public function target_table ( $auth )
    {
        if ( $auth == self::AUTH_OWNER )
        {
            return self::TABLE_OWNER;
        }
        else if ( $auth == self::AUTH_ADMIN )
        {
            return self::TABLE_ADMIN;
        }
    }
    /*====================================================================
        関数名： set_select_admin_id
        概　要： SESSION情報（選択店舗）を登録
    */
    public function set_select_admin_id ( $id )
    {
        $values = array (
            "select_admin_id" => $id,
        );
        return $this->session->set_userdata ( $values );
    }
    /*====================================================================
        関数名： get_select_admin_id
        概　要： SESSION情報（選択店舗）を取得
    */
    public function get_select_admin_id ()
    {
        $returnVal = $this->session->userdata ( "select_admin_id" );
        return ( isset ( $returnVal ) ? $returnVal : self::DEFAULT_ADMIN_ID );
    }
    /*====================================================================
        関数名： clear_select_admin_id
        概　要： SESSION情報（選択店舗）を削除
    */
    public function clear_select_admin_id ()
    {
        return $this->session->unset_userdata( "select_admin_id" );
    }
}