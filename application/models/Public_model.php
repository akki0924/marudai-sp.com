<?php
/*
■機　能： 表画面用ライブラリ
■概　要： 表画面用関連全般
■更新日： 2018/02/02
■担　当： crew.miwa

■更新履歴：
 2018/02/02: 作成開始
*/

class Public_model extends CI_Model {
    // DBテーブル
    const MASTER_TABLE = "m_public";
    // 
    
    // ID生成用文字数
    const CREATE_ID_STRNUM = 10;
    /*====================================================================
        コントラクト
    */
/*
    public function __construct(){
        $this->load->database();
    }
*/
    /*====================================================================
        関数名： detail_values
        概　要： データ一覧を取得
    */
    public function detail_values ( $id = "", $public = false )
    {
        $query = $this->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . password,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . comment,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = '" . Base::add_slashes($id) . "'
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
        return $this->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： name
        概　要： 名前を取得
    */
    public function name ( $id, $public = false )
    {
        return $this->db_lib->GetValue ( self::MASTER_TABLE, 'name', $id, 'id', $public );
    }
    /*====================================================================
        関数名： id_exists
        概　要： IDが存在するかどうか
    */
    public function id_exists ( $id, $public = false )
    {
        return $this->db_lib->ValueExists ( self::MASTER_TABLE, $id, 'id', $public );
    }
    /*====================================================================
        関数名： detail_form_list
        概　要： フォーム用配列
    */
    public function detail_form_list ()
    {
        $returnVal = array (
            'id',
            'writer',
            'visitor',
        );
        return $returnVal;
    }
    /*====================================================================
        関数名： config_values
        概　要： エラーチェック配列
    */
    public function config_values () {
        $returnValues = array(
            array(
                'field'   => 'writer',
                'label'   => '入力担当者',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'visitor',
                'label'   => 'お客様名',
                'rules'   => 'required'
            ),
        );
        return ($returnValues);
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
            $val = $this->input->post_get( $form_list[$i], true );
            if ( $val )
            {
                $values[$form_list[$i]] = $val;
            }
        }
        // 更新
        if ( self::id_exists ($id) )
        {
            return $this->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
        // 新規登録
        else
        {
            return $this->db_lib->Insert ( self::MASTER_TABLE, $values );
        }
    }
    /*====================================================================
        関数名： delete
        概　要： DB削除（論理）
    */
    public function delete(){
        $id = $this->input->get_post('id', true);
        return $this->db_lib->Delete ( self::MASTER_TABLE, true, $id );
    }
}