<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * ログイン用ライブラリー
 *
 * ログインデータの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/06/04：新規作成
 */
class Admin_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = 'm_admin';
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
        $this->SetDbTable('m_admin');
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
                " . self::MASTER_TABLE . " . account,
                " . self::MASTER_TABLE . " . password,
                " . self::MASTER_TABLE . " . company_id,
                " . self::MASTER_TABLE . " . authority,
                " . self::MASTER_TABLE . " . name,
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
    public function GetAccountList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'account', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetPasswordList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'password', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetCompanyIdList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'company_id', $public);
    }


    /**
     * 一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetAuthorityList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'authority', $public);
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
    public function GetAccount(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'account', $id, 'id', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetPassword(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'password', $id, 'id', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetCompanyId(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'company_id', $id, 'id', $public);
    }


    /**
     * を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetAuthority(
        string $id,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'authority', $id, 'id', $public);
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
     * @param string $account：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromAccount(
        string $account,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $account, 'account', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $password：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromPassword(
        string $password,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $password, 'password', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $companyId：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromCompanyId(
        string $companyId,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $companyId, 'company_id', $public);
    }


    /**
     * からIDを取得
     *
     * @param string $authority：対象
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromAuthority(
        string $authority,
        bool $public = false
    ) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $authority, 'authority', $public);
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
     * @param string $account：対象
     * @param boolean $public
     * @return boolean
     */
    public function AccountExists(
        string $account,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $account, 'account', $public);
    }


    /**
     * の登録有無
     *
     * @param string $password：対象
     * @param boolean $public
     * @return boolean
     */
    public function PasswordExists(
        string $password,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $password, 'password', $public);
    }


    /**
     * の登録有無
     *
     * @param string $companyId：対象
     * @param boolean $public
     * @return boolean
     */
    public function CompanyIdExists(
        string $companyId,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $companyId, 'company_id', $public);
    }


    /**
     * の登録有無
     *
     * @param string $authority：対象
     * @param boolean $public
     * @return boolean
     */
    public function AuthorityExists(
        string $authority,
        bool $public = false
    ) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $authority, 'authority', $public);
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
     * @param string $account：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function AccountSameExists(
        $account,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $account, 'account', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $password：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function PasswordSameExists(
        $password,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $password, 'password', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $companyId：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function CompanyIdSameExists(
        $companyId,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $companyId, 'company_id', $id, 'id', $public);
    }


    /**
     * が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $authority：対象
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function AuthoritySameExists(
        $authority,
        $id = '',
        $public = false
    ) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $authority, 'authority', $id, 'id', $public);
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