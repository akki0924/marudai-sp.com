<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 都道府県用ライブラリー
 *
 * 都道府県データの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/06/04：新規作成
 */
class Pref_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = 'm_pref';
    // 表示ステータス
    const ID_STATUS_ENABLE = 1;
    const ID_STATUS_DISABLE = -1;
    const NAME_STATUS_ENABLE = '表示';
    const NAME_STATUS_DISABLE = '非表示';
    // スーパーオブジェクト割当用変数
    protected $CI;


    /**
     * コントラクト
     */
    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // テーブル名をセット
        $this->SetDbTable('m_pref');
    }


    /**
     * IDに対応した詳細データを取得
     *
     * @param string $id：ID
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetDetailValues(string $id = '', bool $public = false) : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // SQL
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                CASE " . self::MASTER_TABLE . " . status
                    WHEN " . self::ID_STATUS_ENABLE . " THEN '" . self::NAME_STATUS_ENABLE . "'
                    ELSE '" . self::NAME_STATUS_DISABLE . "'
                END AS status_name,
                " . self::MASTER_TABLE . " . regist_date,
                DATE_FORMAT(" . self::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . self::MASTER_TABLE . " . edit_date,
                DATE_FORMAT(" . self::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = " . $this->CI->db_lib->SetWhereVar($id) . "
                " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base_lib::STATUS_ENABLE : "") . "
            )
            ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $resultList = $query->result_array();
            foreach ($resultList[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        return $returnVal;
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetIdList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'id', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSortIdList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sort_id', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetRegistDateList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'regist_date', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetEditDateList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'edit_date', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetId(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $id, 'id', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetRegistDate(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'regist_date', $id, 'id', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetEditDate(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'edit_date', $id, 'id', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $id：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromId(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $id, 'id', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $status：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromStatus(
        string $status,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $status, 'status', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $registDate：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromRegistDate(
        string $registDate,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $registDate, 'regist_date', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $editDate：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromEditDate(
        string $editDate,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $editDate, 'edit_date', $public);
    }


    /**
     * の登録有無
     *
     * @param string $status：対象
     * @param boolean $public
     * @return boolean
     */
    public function StatusExists(
        string $status,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $status, 'status', $public);
    }


    /**
     * の登録有無
     *
     * @param string $registDate：対象
     * @param boolean $public
     * @return boolean
     */
    public function RegistDateExists(
        string $registDate,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $registDate, 'regist_date', $public);
    }


    /**
     * の登録有無
     *
     * @param string $editDate：対象
     * @param boolean $public
     * @return boolean
     */
    public function EditDateExists(
        string $editDate,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $editDate, 'edit_date', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $id：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function IdSameExists(
        $id,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $id, 'id', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $status：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function StatusSameExists(
        $status,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $status, 'status', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $registDate：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function RegistDateSameExists(
        $registDate,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $registDate, 'regist_date', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $editDate：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function EditDateSameExists(
        $editDate,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $editDate, 'edit_date', $id, 'id', $public);
    }


}