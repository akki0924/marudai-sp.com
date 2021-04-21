<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能 : 指定席用処理ライブラリー
    ■概　要 : 指定席用登録関数群
    ■更新日 : 2021/04/05
    ■担　当 : crew.miwa

    ■更新履歴 :
    2021/04/05: 作成開始

    */

class Seat_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_seat";                  // 指定席マスタ
    // ID生成文字列数
    const ID_STR_NUM = 10;
    // 表示ステータス名
    const NAME_STATUS_ENABLE = '使用可能';
    const NAME_STATUS_DISABLE = '使用不可能';
    // 指定席画像数
    const IMG_COUNT = 3;
    // 指定席画像削除用SESSION追加文字列
    const IMG_DEL_SESSION_ADD_STR = '_del';
    // 座席タイプ
    const ID_TYPE_BASIC = 1;               // 指定席
    const ID_TYPE_WHEELCHAIR = 2;          // 車いす席
    const ID_TYPE_SPECIAL = 3;             // グループ席
    const NAME_TYPE_BASIC = '指定席';
    const NAME_TYPE_WHEELCHAIR = '車いす席';
    const NAME_TYPE_SPECIAL = 'グループ席';

    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名 : GetDetailValues
        概　要 : 詳細データを取得
        引　数 : $id : 対象指定席ID
                $public : ステータスフラグ
    */
    public function GetDetailValues($id = "", $public = false)
    {
        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/reserve_lib');

        $returnVal = array();

        $query = $this->CI->db->query("
            SELECT
                m_seat01 . id,
                m_seat01 . name,
                m_seat01 . type,
                m_seat01 . num,
                m_seat01 . sort_id,
                m_seat01 . status,
                m_seat01 . regist_date,
                DATE_FORMAT(m_seat01 . regist_date, '%Y.%c.%e') AS regist_date_disp,
                m_seat01 . edit_date,
                DATE_FORMAT(m_seat01.edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . self::MASTER_TABLE . " m_seat01
            LEFT OUTER JOIN " . Reserve_lib::DATA_TABLE . " d_order01 ON m_seat01 . id = d_order01 . seat_id
            WHERE (
                m_seat01 . id = '" . Base_lib::AddSlashes($id) . "'
                " . ($public ? " AND m_seat01 . status >= " . Base_lib::STATUS_ENABLE : "") . "
            )
            ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $result_list = $query->result_array();
            foreach ($result_list[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
            // 座席タイプ名
            $returnVal['type_name'] = $this->GetTypeName($returnVal['type']);
        }

        return $returnVal;
    }
    /*====================================================================
        関数名 : GetSelectNameList
        概　要 : 一覧を取得
        引　数 : $whereSql : WHERE情報を配列形式
    */
    public function GetSelectNameList($whereSql = '')
    {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報を再セット
        if (! is_array($whereSql)) {
            $whereSql = [];
        }
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . name
            FROM " . self::MASTER_TABLE . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            ORDER BY " . self::MASTER_TABLE . " . sort_id ASC
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetNameList
        概　要 : 指定席名一覧を取得
        引　数 : $public : ステータスフラグ
    */
    public function GetNameList($public = false)
    {
        return $this->CI->db_lib->GetSelectValues(self::MASTER_TABLE, 'name', $public);
    }
    /*====================================================================
        関数名 : GetNameOnlyList
        概　要 : 指定席名一覧を取得（キーも指定席名）
        引　数 : $public : ステータスフラグ
    */
    public function GetNameOnlyList($public = false)
    {
        // SQL文
        $query = $this->CI->db->query("
            SELECT name
            FROM " . self::MASTER_TABLE . "
            " . ($public ? "WHERE status >= " . Base_lib::STATUS_ENABLE : "") . "
            ORDER BY sort_id ASC;

        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // CordIgniter用配列にセット
                $returnVal[$row->name] = $row->name;
            }
        } else {
            $returnVal = "";
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetName
        概　要 : 名前を取得
        引　数 : $id : 対象指定席ID
                $public : ステータスフラグ
    */
    public function GetName($id, $public = false)
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'name', $id, 'id', $public);
    }
    /*====================================================================
        関数名 : GetSortId
        概　要 : 並び順を取得
        引　数 : $id : 対象指定席ID
                $public : ステータスフラグ
    */
    public function GetSortId($id, $public = false)
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'sort_id', $id, 'id', $public);
    }
    /*====================================================================
        関数名 : GetSortIdForId
        概　要 : 対象並び順から指定席IDを取得
        引　数 : $sortId : 並び順
                $public : ステータスフラグ
    */
    public function GetSortIdForId($sortId, $public = false)
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $sortId, 'sort_id', $public);
    }
    /*====================================================================
        関数名 : IdExists
        概　要 : IDが存在するかどうか
        引　数 : $id : 対象指定席ID
                $public : ステータスフラグ
    */
    public function IdExists($id, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $id, 'id', $public);
    }
    /*====================================================================
        関数名 : NameExists
        概　要 : 名前が存在するかどうか
        引　数 : $name : 対象指定席名
                $public : ステータスフラグ
    */
    public function NameExists($name, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $name, 'name', $public);
    }
    /*====================================================================
        関数名 : SelectExists
        概　要 : 対象情報が存在するかどうか
        引　数 : $whereSql : Where文（配列）
    */
    public function SelectExists($whereSql = array())
    {
        // SQL文
        $query = $this->CI->db->query("
            SELECT COUNT(id) AS count
            FROM " . self::MASTER_TABLE . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $list = $query->result_array();
        }
        return ($list[0]['count'] > 0 ? true : false);
    }
    /*====================================================================
        関数名 : NameSameExists
        概　要 : 座席番号が対象ID以外に同じ値が存在するかどうか
        引　数 : $name : 座席番号
                $public : ステータスフラグ
    */
    public function NameSameExists($name, $id = '', $public = false)
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $name, 'name', $id, 'id', $public);
    }
    /*====================================================================
        関数名 : CreateId
        概　要 : IDを生成
        引　数 : $public : ステータスフラグ
    */
    public function CreateId($public = false)
    {
        // 未登録のランダム文字列を生成
        return $this->CI->db_lib->CreateStr(self::MASTER_TABLE, 'id', self::ID_STR_NUM, $public);
    }
    /*====================================================================
        関数名 : RegistData
        概　要 : DB登録処理
        引　数 : $registData : 登録内容連想配列（key : 対象カラム, value : 値）
                $id : 対象指定席ID
    */
    public function RegistData($registData = '', $id = '')
    {
        // 返り値をセット
        $returnVal = false;
        // 配列形式の確認
        if (is_array($registData)) {
            // ユーザーIDが登録されているか
            if ($this->IdExists($id)) {
                // 登録情報にIDをセット
                $registData['id'] = $id;
                // 更新処理
                $returnVal = $this->CI->db_lib->Update(self::MASTER_TABLE, $registData, $id);
            } else {
                // IDが未セットの場合、IDを生成
                if (! isset($registData['id']) || $registData['id'] == '') {
                    $registData['id'] = $this->CreateId();
                }
                // 新規作成
                $returnVal = $this->CI->db_lib->Insert(self::MASTER_TABLE, $registData);
            }
            // 登録成功の場合、IDを返す
            if ($returnVal) {
                $returnVal = $registData['id'];
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : SelectDelete
        概　要 : DB削除処理（タグのみ）
        引　数 : $id : 対象指定席ID
    */
    public function SelectDelete($id)
    {
        // 指定席IDが登録されているか
        if ($this->IdExists($id, true)) {
            // 更新処理
            return $this->CI->db_lib->Delete(self::MASTER_TABLE, true, $id);
        }
    }
    /**
     * 表示ステータス一覧を配列形式で取得
     *
     * @return array
     */
    public function GetStatusList() : array
    {
        $returnVal[Base_lib::STATUS_ENABLE] = self::NAME_STATUS_ENABLE;
        $returnVal[Base_lib::STATUS_DISABLE] = self::NAME_STATUS_DISABLE;

        return $returnVal;
    }
    /**
     * 表示ステータス名を取得
     *
     * @param string $id
     * @return string
     */
    public function GetStatusName($id) : string
    {
        // 一覧リストを取得
        $list = $this->GetStatusList();
        return (isset($list[ $id ]) ? $list[ $id ] : '');
    }
    /**
     * 表示ステータスの存在確認結果を取得
     *
     * @param string $id
     * @return bool
     */
    public function GetStatusExists($id) : bool
    {
        // 一覧リストを取得
        $list = $this->GetStatusList();
        return (isset($list[ $id ]) ? true : false);
    }
    /**
     * 座席タイプ一覧を配列形式で取得
     *
     * @return array
     */
    public function GetTypeList() : array
    {
        $returnVal[self::ID_TYPE_BASIC] = self::NAME_TYPE_BASIC;
        $returnVal[self::ID_TYPE_WHEELCHAIR] = self::NAME_TYPE_WHEELCHAIR;
        $returnVal[self::ID_TYPE_SPECIAL] = self::NAME_TYPE_SPECIAL;

        return $returnVal;
    }
    /**
     * 座席タイプ名を取得
     *
     * @param string $id
     * @return string
     */
    public function GetTypeName($id) : string
    {
        // 一覧リストを取得
        $list = $this->GetTypeList();
        return (isset($list[ $id ]) ? $list[ $id ] : '');
    }
    /**
     * 座席タイプの存在確認結果を取得
     *
     * @param string $id
     * @return bool
     */
    public function GetTypeExists($id) : bool
    {
        // 一覧リストを取得
        $list = $this->GetTypeList();
        return (isset($list[ $id ]) ? true : false);
    }
}
