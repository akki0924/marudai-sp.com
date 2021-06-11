<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 登録者用ライブラリー
 *
 * 登録者データの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/06/11：新規作成
 */
class User_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = 'm_user';
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
                " . self::MASTER_TABLE . " . eco_id,
                " . self::MASTER_TABLE . " . nickname,
                " . self::MASTER_TABLE . " . sheet1_1_1,
                " . self::MASTER_TABLE . " . sheet1_1_2,
                " . self::MASTER_TABLE . " . sheet1_1_3,
                " . self::MASTER_TABLE . " . sheet1_1_4,
                " . self::MASTER_TABLE . " . sheet1_1_5,
                " . self::MASTER_TABLE . " . sheet1_1_6,
                " . self::MASTER_TABLE . " . sheet1_1_7,
                " . self::MASTER_TABLE . " . sheet1_2_1,
                " . self::MASTER_TABLE . " . sheet1_2_2,
                " . self::MASTER_TABLE . " . sheet1_2_3,
                " . self::MASTER_TABLE . " . sheet1_2_4,
                " . self::MASTER_TABLE . " . sheet1_2_5,
                " . self::MASTER_TABLE . " . sheet1_2_6,
                " . self::MASTER_TABLE . " . sheet1_2_7,
                " . self::MASTER_TABLE . " . sheet1_3_1,
                " . self::MASTER_TABLE . " . sheet1_3_2,
                " . self::MASTER_TABLE . " . sheet1_3_3,
                " . self::MASTER_TABLE . " . sheet1_3_4,
                " . self::MASTER_TABLE . " . sheet1_3_5,
                " . self::MASTER_TABLE . " . sheet1_3_6,
                " . self::MASTER_TABLE . " . sheet1_3_7,
                " . self::MASTER_TABLE . " . sheet1_4_1,
                " . self::MASTER_TABLE . " . sheet1_4_2,
                " . self::MASTER_TABLE . " . sheet1_4_3,
                " . self::MASTER_TABLE . " . sheet1_4_4,
                " . self::MASTER_TABLE . " . sheet1_4_5,
                " . self::MASTER_TABLE . " . sheet1_4_6,
                " . self::MASTER_TABLE . " . sheet1_4_7,
                " . self::MASTER_TABLE . " . sheet1_5_1,
                " . self::MASTER_TABLE . " . sheet1_5_2,
                " . self::MASTER_TABLE . " . sheet1_5_3,
                " . self::MASTER_TABLE . " . sheet1_5_4,
                " . self::MASTER_TABLE . " . sheet1_5_5,
                " . self::MASTER_TABLE . " . sheet1_5_6,
                " . self::MASTER_TABLE . " . sheet1_5_7,
                " . self::MASTER_TABLE . " . sheet2_1,
                " . self::MASTER_TABLE . " . sheet2_2,
                " . self::MASTER_TABLE . " . sheet2_3,
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
     * エコアップID一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetEcoIdList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'eco_id', $public);
    }


    /**
     * ニックネーム一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetNicknameList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'nickname', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet111List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_1', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet112List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_2', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet113List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_3', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet114List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_4', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet115List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_5', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet116List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_6', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet117List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_1_7', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet121List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_1', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet122List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_2', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet123List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_3', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet124List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_4', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet125List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_5', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet126List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_6', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet127List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_2_7', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet131List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_1', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet132List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_2', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet133List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_3', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet134List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_4', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet135List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_5', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet136List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_6', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet137List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_3_7', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet141List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_1', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet142List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_2', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet143List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_3', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet144List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_4', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet145List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_5', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet146List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_6', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet147List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_4_7', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet151List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_1', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet152List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_2', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet153List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_3', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet154List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_4', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet155List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_5', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet156List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_6', $public);
    }


    /**
     * シート1一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet157List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet1_5_7', $public);
    }


    /**
     * シート2一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet21List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet2_1', $public);
    }


    /**
     * シート2一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet22List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet2_2', $public);
    }


    /**
     * シート2一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetSheet23List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'sheet2_3', $public);
    }


    /**
     * エコアップIDを取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetEcoId(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'eco_id', $id, 'id', $public);
    }


    /**
     * ニックネームを取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetNickname(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'nickname', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet111(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_1', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet112(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_2', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet113(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_3', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet114(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_4', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet115(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_5', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet116(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_6', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet117(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_1_7', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet121(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_1', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet122(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_2', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet123(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_3', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet124(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_4', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet125(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_5', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet126(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_6', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet127(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_2_7', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet131(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_1', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet132(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_2', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet133(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_3', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet134(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_4', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet135(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_5', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet136(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_6', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet137(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_3_7', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet141(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_1', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet142(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_2', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet143(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_3', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet144(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_4', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet145(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_5', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet146(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_6', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet147(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_4_7', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet151(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_1', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet152(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_2', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet153(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_3', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet154(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_4', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet155(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_5', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet156(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_6', $id, 'id', $public);
    }


    /**
     * シート1を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet157(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet1_5_7', $id, 'id', $public);
    }


    /**
     * シート2を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet21(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet2_1', $id, 'id', $public);
    }


    /**
     * シート2を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet22(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet2_2', $id, 'id', $public);
    }


    /**
     * シート2を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSheet23(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sheet2_3', $id, 'id', $public);
    }


    /**
     * エコアップIDからIDを取得
     *
     * @param string $ecoId：対象エコアップID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromEcoId(
        string $ecoId,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $ecoId, 'eco_id', $public);
    }


    /**
     * ニックネームからIDを取得
     *
     * @param string $nickname：対象ニックネーム
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromNickname(
        string $nickname,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $nickname, 'nickname', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet111：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet111(
        string $sheet111,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet111, 'sheet1_1_1', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet112：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet112(
        string $sheet112,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet112, 'sheet1_1_2', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet113：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet113(
        string $sheet113,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet113, 'sheet1_1_3', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet114：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet114(
        string $sheet114,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet114, 'sheet1_1_4', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet115：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet115(
        string $sheet115,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet115, 'sheet1_1_5', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet116：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet116(
        string $sheet116,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet116, 'sheet1_1_6', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet117：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet117(
        string $sheet117,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet117, 'sheet1_1_7', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet121：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet121(
        string $sheet121,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet121, 'sheet1_2_1', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet122：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet122(
        string $sheet122,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet122, 'sheet1_2_2', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet123：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet123(
        string $sheet123,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet123, 'sheet1_2_3', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet124：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet124(
        string $sheet124,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet124, 'sheet1_2_4', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet125：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet125(
        string $sheet125,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet125, 'sheet1_2_5', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet126：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet126(
        string $sheet126,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet126, 'sheet1_2_6', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet127：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet127(
        string $sheet127,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet127, 'sheet1_2_7', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet131：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet131(
        string $sheet131,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet131, 'sheet1_3_1', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet132：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet132(
        string $sheet132,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet132, 'sheet1_3_2', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet133：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet133(
        string $sheet133,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet133, 'sheet1_3_3', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet134：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet134(
        string $sheet134,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet134, 'sheet1_3_4', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet135：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet135(
        string $sheet135,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet135, 'sheet1_3_5', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet136：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet136(
        string $sheet136,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet136, 'sheet1_3_6', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet137：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet137(
        string $sheet137,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet137, 'sheet1_3_7', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet141：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet141(
        string $sheet141,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet141, 'sheet1_4_1', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet142：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet142(
        string $sheet142,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet142, 'sheet1_4_2', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet143：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet143(
        string $sheet143,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet143, 'sheet1_4_3', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet144：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet144(
        string $sheet144,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet144, 'sheet1_4_4', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet145：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet145(
        string $sheet145,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet145, 'sheet1_4_5', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet146：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet146(
        string $sheet146,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet146, 'sheet1_4_6', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet147：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet147(
        string $sheet147,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet147, 'sheet1_4_7', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet151：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet151(
        string $sheet151,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet151, 'sheet1_5_1', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet152：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet152(
        string $sheet152,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet152, 'sheet1_5_2', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet153：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet153(
        string $sheet153,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet153, 'sheet1_5_3', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet154：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet154(
        string $sheet154,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet154, 'sheet1_5_4', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet155：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet155(
        string $sheet155,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet155, 'sheet1_5_5', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet156：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet156(
        string $sheet156,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet156, 'sheet1_5_6', $public);
    }


    /**
     * シート1からIDを取得
     *
     * @param string $sheet157：対象シート1
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet157(
        string $sheet157,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet157, 'sheet1_5_7', $public);
    }


    /**
     * シート2からIDを取得
     *
     * @param string $sheet21：対象シート2
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet21(
        string $sheet21,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet21, 'sheet2_1', $public);
    }


    /**
     * シート2からIDを取得
     *
     * @param string $sheet22：対象シート2
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet22(
        string $sheet22,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet22, 'sheet2_2', $public);
    }


    /**
     * シート2からIDを取得
     *
     * @param string $sheet23：対象シート2
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSheet23(
        string $sheet23,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sheet23, 'sheet2_3', $public);
    }


    /**
     * エコアップIDの登録有無
     *
     * @param string $ecoId：対象エコアップID
     * @param boolean $public
     * @return boolean
     */
    public function EcoIdExists(
        string $ecoId,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $ecoId, 'eco_id', $public);
    }


    /**
     * ニックネームの登録有無
     *
     * @param string $nickname：対象ニックネーム
     * @param boolean $public
     * @return boolean
     */
    public function NicknameExists(
        string $nickname,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $nickname, 'nickname', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet111：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet111Exists(
        string $sheet111,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet111, 'sheet1_1_1', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet112：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet112Exists(
        string $sheet112,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet112, 'sheet1_1_2', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet113：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet113Exists(
        string $sheet113,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet113, 'sheet1_1_3', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet114：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet114Exists(
        string $sheet114,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet114, 'sheet1_1_4', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet115：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet115Exists(
        string $sheet115,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet115, 'sheet1_1_5', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet116：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet116Exists(
        string $sheet116,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet116, 'sheet1_1_6', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet117：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet117Exists(
        string $sheet117,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet117, 'sheet1_1_7', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet121：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet121Exists(
        string $sheet121,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet121, 'sheet1_2_1', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet122：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet122Exists(
        string $sheet122,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet122, 'sheet1_2_2', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet123：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet123Exists(
        string $sheet123,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet123, 'sheet1_2_3', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet124：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet124Exists(
        string $sheet124,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet124, 'sheet1_2_4', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet125：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet125Exists(
        string $sheet125,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet125, 'sheet1_2_5', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet126：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet126Exists(
        string $sheet126,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet126, 'sheet1_2_6', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet127：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet127Exists(
        string $sheet127,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet127, 'sheet1_2_7', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet131：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet131Exists(
        string $sheet131,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet131, 'sheet1_3_1', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet132：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet132Exists(
        string $sheet132,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet132, 'sheet1_3_2', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet133：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet133Exists(
        string $sheet133,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet133, 'sheet1_3_3', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet134：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet134Exists(
        string $sheet134,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet134, 'sheet1_3_4', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet135：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet135Exists(
        string $sheet135,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet135, 'sheet1_3_5', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet136：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet136Exists(
        string $sheet136,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet136, 'sheet1_3_6', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet137：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet137Exists(
        string $sheet137,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet137, 'sheet1_3_7', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet141：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet141Exists(
        string $sheet141,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet141, 'sheet1_4_1', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet142：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet142Exists(
        string $sheet142,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet142, 'sheet1_4_2', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet143：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet143Exists(
        string $sheet143,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet143, 'sheet1_4_3', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet144：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet144Exists(
        string $sheet144,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet144, 'sheet1_4_4', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet145：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet145Exists(
        string $sheet145,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet145, 'sheet1_4_5', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet146：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet146Exists(
        string $sheet146,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet146, 'sheet1_4_6', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet147：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet147Exists(
        string $sheet147,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet147, 'sheet1_4_7', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet151：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet151Exists(
        string $sheet151,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet151, 'sheet1_5_1', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet152：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet152Exists(
        string $sheet152,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet152, 'sheet1_5_2', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet153：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet153Exists(
        string $sheet153,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet153, 'sheet1_5_3', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet154：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet154Exists(
        string $sheet154,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet154, 'sheet1_5_4', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet155：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet155Exists(
        string $sheet155,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet155, 'sheet1_5_5', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet156：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet156Exists(
        string $sheet156,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet156, 'sheet1_5_6', $public);
    }


    /**
     * シート1の登録有無
     *
     * @param string $sheet157：対象シート1
     * @param boolean $public
     * @return boolean
     */
    public function Sheet157Exists(
        string $sheet157,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet157, 'sheet1_5_7', $public);
    }


    /**
     * シート2の登録有無
     *
     * @param string $sheet21：対象シート2
     * @param boolean $public
     * @return boolean
     */
    public function Sheet21Exists(
        string $sheet21,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet21, 'sheet2_1', $public);
    }


    /**
     * シート2の登録有無
     *
     * @param string $sheet22：対象シート2
     * @param boolean $public
     * @return boolean
     */
    public function Sheet22Exists(
        string $sheet22,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet22, 'sheet2_2', $public);
    }


    /**
     * シート2の登録有無
     *
     * @param string $sheet23：対象シート2
     * @param boolean $public
     * @return boolean
     */
    public function Sheet23Exists(
        string $sheet23,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sheet23, 'sheet2_3', $public);
    }


    /**
     * エコアップIDが対象ID以外に同じ値が存在するかどうか
     *
     * @param string $ecoId：対象エコアップID
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function EcoIdSameExists(
        $ecoId,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $ecoId, 'eco_id', $id, 'id', $public);
    }


    /**
     * ニックネームが対象ID以外に同じ値が存在するかどうか
     *
     * @param string $nickname：対象ニックネーム
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function NicknameSameExists(
        $nickname,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $nickname, 'nickname', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet111：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet111SameExists(
        $sheet111,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet111, 'sheet1_1_1', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet112：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet112SameExists(
        $sheet112,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet112, 'sheet1_1_2', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet113：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet113SameExists(
        $sheet113,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet113, 'sheet1_1_3', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet114：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet114SameExists(
        $sheet114,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet114, 'sheet1_1_4', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet115：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet115SameExists(
        $sheet115,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet115, 'sheet1_1_5', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet116：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet116SameExists(
        $sheet116,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet116, 'sheet1_1_6', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet117：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet117SameExists(
        $sheet117,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet117, 'sheet1_1_7', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet121：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet121SameExists(
        $sheet121,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet121, 'sheet1_2_1', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet122：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet122SameExists(
        $sheet122,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet122, 'sheet1_2_2', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet123：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet123SameExists(
        $sheet123,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet123, 'sheet1_2_3', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet124：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet124SameExists(
        $sheet124,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet124, 'sheet1_2_4', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet125：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet125SameExists(
        $sheet125,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet125, 'sheet1_2_5', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet126：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet126SameExists(
        $sheet126,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet126, 'sheet1_2_6', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet127：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet127SameExists(
        $sheet127,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet127, 'sheet1_2_7', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet131：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet131SameExists(
        $sheet131,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet131, 'sheet1_3_1', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet132：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet132SameExists(
        $sheet132,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet132, 'sheet1_3_2', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet133：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet133SameExists(
        $sheet133,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet133, 'sheet1_3_3', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet134：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet134SameExists(
        $sheet134,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet134, 'sheet1_3_4', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet135：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet135SameExists(
        $sheet135,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet135, 'sheet1_3_5', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet136：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet136SameExists(
        $sheet136,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet136, 'sheet1_3_6', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet137：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet137SameExists(
        $sheet137,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet137, 'sheet1_3_7', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet141：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet141SameExists(
        $sheet141,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet141, 'sheet1_4_1', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet142：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet142SameExists(
        $sheet142,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet142, 'sheet1_4_2', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet143：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet143SameExists(
        $sheet143,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet143, 'sheet1_4_3', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet144：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet144SameExists(
        $sheet144,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet144, 'sheet1_4_4', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet145：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet145SameExists(
        $sheet145,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet145, 'sheet1_4_5', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet146：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet146SameExists(
        $sheet146,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet146, 'sheet1_4_6', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet147：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet147SameExists(
        $sheet147,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet147, 'sheet1_4_7', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet151：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet151SameExists(
        $sheet151,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet151, 'sheet1_5_1', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet152：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet152SameExists(
        $sheet152,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet152, 'sheet1_5_2', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet153：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet153SameExists(
        $sheet153,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet153, 'sheet1_5_3', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet154：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet154SameExists(
        $sheet154,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet154, 'sheet1_5_4', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet155：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet155SameExists(
        $sheet155,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet155, 'sheet1_5_5', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet156：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet156SameExists(
        $sheet156,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet156, 'sheet1_5_6', $id, 'id', $public);
    }


    /**
     * シート1が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet157：対象シート1
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet157SameExists(
        $sheet157,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet157, 'sheet1_5_7', $id, 'id', $public);
    }


    /**
     * シート2が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet21：対象シート2
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet21SameExists(
        $sheet21,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet21, 'sheet2_1', $id, 'id', $public);
    }


    /**
     * シート2が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet22：対象シート2
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet22SameExists(
        $sheet22,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet22, 'sheet2_2', $id, 'id', $public);
    }


    /**
     * シート2が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sheet23：対象シート2
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function Sheet23SameExists(
        $sheet23,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sheet23, 'sheet2_3', $id, 'id', $public);
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