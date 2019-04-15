<?php
/*
■機　能： 媒介情報用ライブラリ
■概　要： ランク情報用関連全般
■更新日： 2018/01/18
■担　当： crew.miwa

■更新履歴：
 2018/01/18: 作成開始
*/

class Config_model extends CI_Model {
    // DBテーブル
    const MASTER_TABLE = "m_config";
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
    public function detail_values($id = "", $public = false){
        // WHERE文をセット
        $whereSql[] = Object_model::MASTER_TABLE . " . id = '" . Base::add_slashes($id) . "'";
        if ($public) {
            $whereSql[] = Object_model::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE;
        }
        // SQL文をセット
        $query = $this->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . title,
                " . self::MASTER_TABLE . " . value,
                " . self::MASTER_TABLE . " . comment,
            FROM " . self::MASTER_TABLE . "
            " . (count($whereSql) > 0 ? (@implode (" AND ", $whereSql)) . " AND " : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $result_list = $query->result_array();
            foreach ($result_list[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： all_values
        概　要： 全データ一覧を取得
    */
    public function all_values ($public = false) {
        $query = $this->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . title,
                " . self::MASTER_TABLE . " . value,
                " . self::MASTER_TABLE . " . comment,
                DATE_FORMAT(" . self::MASTER_TABLE . " . edit_date, "%Y年%m月%d日") AS edit_date_disp
            FROM " . self::MASTER_TABLE . "
            " . ($public ? "WHERE " . self::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE : "") . "
            ORDER BY " . self::MASTER_TABLE . " . sort_id ASC;
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $resultList = $query->result_array();
        }
        else {
            $resultList = "";
        }
        return $resultList;
    }
    /*====================================================================
        関数名： value
        概　要： 値を取得
    */
    public function name ($id, $public = false) {
        $query = $this->db->query("
            SELECT
                " . self::MASTER_TABLE . " . value
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = '" . Base::add_slashes($id) . "'
                " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE : "") . "
            )
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return $row->value;
            }
        }
    }
    /*====================================================================
        関数名： id_exists
        概　要： IDが存在するかどうか
    */
    public function id_exists ($id, $public = false) {
        $query = $this->db->query("
            SELECT COUNT(*) AS count
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = " . Base::empty_to_null($id) . "
                " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE : "") . "
            )
        ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return ($row->count > 0 ? true : false);
            }
        }
    }
    /*====================================================================
        関数名： create_sort_id
        概　要： 最大値を取得を取得
    */
    public function create_sort_id ($public = false) {
        $query = $this->db->query("
            SELECT
                MAX(" . self::MASTER_TABLE . " . sort_id) AS max_sort_id
            FROM " . self::MASTER_TABLE . "
            " . ($public ? " WHERE " . self::MASTER_TABLE . " . status >= " . Base::STATUS_ENABLE : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return $row->max_sort_id + 1;
            }
        }
        else {
            return 1;
        }
    }
    /*====================================================================
        関数名： insert
        概　要： DB新規追加
    */
    public function insert($values){
        // オブジェクト又は、配列の場合
        if (
            is_object ($values) ||
            is_array ($values)
        )
        {
            // 登録情報をセット
            foreach ($values AS $values_key => $values_val)
            {
                $form_values[$values_key] = $values_val;
            }
            // ステータス情報を追加
            $form_values['status'] = Base::STATUS_ENABLE;
            // 順番
            $form_values['sort_id'] = self::create_sort_id (true);
            // 新規登録処理
            return $this->db->insert(self::MASTER_TABLE, $form_values);
        }
    }
    /*====================================================================
        関数名： update
        概　要： DB更新
    */
    public function update(){
        // フォーム情報
        $val = $this->input->get_post('name', true);
        if ($val) {
            // 名前
            $form_values['name'] = $val;
            
            $this->db->where('id', $form_values['id']);
            return $this->db->update(self::MASTER_TABLE, $form_values);
        }
    }
    /*====================================================================
        関数名： delete
        概　要： DB削除（論理）
    */
    public function delete(){
        $id = $this->input->get_post('id', true);
        $form_values['status'] = Base::STATUS_DISABLE;
        if ($id != '') {
            $this->db->where('id', $id);
            return $this->db->update(self::MASTER_TABLE, $form_values);
        }
    }
}