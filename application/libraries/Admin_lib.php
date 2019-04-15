<?php
    /*
    ■機　能： 管理者情報用ライブラリ
    ■概　要： 管理者情報用関連全般
    ■更新日： 2019/01/17
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/17: 作成開始
     
    */

class Admin_lib {
    // DBテーブル
    const MASTER_TABLE = "m_admin";
    // ID生成用文字数
    const CREATE_ID_STRNUM = 10;
    
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct(){
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名： detail_values
        概　要： データ一覧を取得
    */
    public function detail_values ( $id = "", $public = false )
    {
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . account,
                " . self::MASTER_TABLE . " . password,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = " . Base::empty_to_null($id) . "
            " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE : "") . "
            );
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $result_list = $query->result_array();
            foreach ( $result_list[0] as $key => $val )
            {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        else
        {
            $returnVal = "";
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： select_values
        概　要： データ一覧を取得
    */
    public function select_values ($public = false)
    {
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： name
        概　要： 名前を取得
    */
    public function name ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'name', $id, 'id', $public );
    }
    /*====================================================================
        関数名： id_exists
        概　要： IDが存在するかどうか
    */
    public function id_exists ( $id, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $id, 'id', $public );
    }
    /*====================================================================
        関数名： login_action
        概　要： ログイン処理
    */
    public function login_action ( $account, $password )
    {
        return $this->CI->login_model->execute ( Login_model::AUTH_ADMIN, true, $account, $password );
    }
    /*====================================================================
        関数名： regist
        概　要： DB登録・更新
    */
    public function regist( $id = '' )
    {
        // フォーム情報
        $form_list = self::detail_form_list ();
        for ( $i = 0, $n = count ($form_list); $i < $n; $i ++ )
        {
            $val = $this->CI->input->post_get( $form_list[$i], true );
            if ( $val )
            {
                $values[$form_list[$i]] = $val;
            }
        }
        // 更新
        if ( self::id_exists ($id) )
        {
            return $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
        // 新規登録
        else
        {
            return $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
        }
    }
    /*====================================================================
        関数名： delete
        概　要： DB削除（論理）
    */
    public function delete(){
        $id = $this->CI->input->get_post('id', true);
        return $this->CI->db_lib->Delete ( self::MASTER_TABLE, true, $id );
    }
}