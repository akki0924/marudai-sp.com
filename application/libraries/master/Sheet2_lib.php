<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * チェックシート2用ライブラリー
 *
 * チェックシート2データの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/06/11：新規作成
 */
class Sheet2_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = 'm_sheet2';
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
        $this->SetDbTable(self::MASTER_TABLE);
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
                " . self::MASTER_TABLE . " . no,
                " . self::MASTER_TABLE . " . contents,
                " . self::MASTER_TABLE . " . point,
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
     * ナンバー一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetNoList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'no', $public);
    }


    /**
     * 点数一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetPointList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'point', $public);
    }


    /**
     * ナンバーを取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetNo(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'no', $id, 'id', $public);
    }


    /**
     * 点数を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetPoint(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'point', $id, 'id', $public);
    }


    /**
     * ナンバーからIDを取得
     *
     * @param string $no：対象ナンバー
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromNo(
        string $no,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $no, 'no', $public);
    }


    /**
     * 点数からIDを取得
     *
     * @param string $point：対象点数
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromPoint(
        string $point,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $point, 'point', $public);
    }


    /**
     * ナンバーの登録有無
     *
     * @param string $no：対象ナンバー
     * @param boolean $public
     * @return boolean
     */
    public function NoExists(
        string $no,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $no, 'no', $public);
    }


    /**
     * 点数の登録有無
     *
     * @param string $point：対象点数
     * @param boolean $public
     * @return boolean
     */
    public function PointExists(
        string $point,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $point, 'point', $public);
    }


    /**
     * ナンバーが対象ID以外に同じ値が存在するかどうか
     *
     * @param string $no：対象ナンバー
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function NoSameExists(
        $no,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $no, 'no', $id, 'id', $public);
    }


    /**
     * 点数が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $point：対象点数
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function PointSameExists(
        $point,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $point, 'point', $id, 'id', $public);
    }


    /**
     * 表示ステータス一覧を配列形式で取得
     *
     * @return array
     */
    public function GetStatusList() : array
    {
        $returnVal[self::ID_STATUS_ENABLE] = self::NAME_STATUS_ENABLE;
        $returnVal[self::ID_STATUS_DISABLE] = self::NAME_STATUS_DISABLE;

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




}