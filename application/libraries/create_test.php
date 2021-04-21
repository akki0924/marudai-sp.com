<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * テストデータ用ライブラリー
 *
 * テストデータの取得および処理する為の関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     2021/04/21：新規作成
 */
class Test{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = "m_test";                  // 指定席マスタ
    // 表示ステータス
    const ID_STATUS_OK = '1';
    const ID_STATUS_NG = '-1';
    const NAME_STATUS_OK = '大丈夫だよ';
    const NAME_STATUS_NG = '大丈夫じゃないよ';

    // スーパーオブジェクト割当用変数
    protected $CI;


    /**
     * コントラクト
     */
    public function __construct()
    {
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }


    /**
     * IDに対応した詳細データを取得
     *
     * @param string $id:ID
     * @param boolean $public:ステータスフラグ
     * @return array|null
     */
    public function GetDetailValues($id = "", $public = false) : ?array
    {
        // 返値を初期化
        $returnVal = array();

        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/reserve_lib');
        // SQL
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = '" . Base_lib::AddSlashes($id) . "'
                " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base_lib::STATUS_ENABLE : "") . "
            )
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



    /**
     * 名前一覧を取得
     *
     * @param bool $public
     * @return array|null
     */
    public function GetNameList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues(self::MASTER_TABLE, 'name', $public);
    }


    /**
     * 名前を取得
     *
     * @param string $id
     * @param boolean $public
     * @return string|null
     */
    public function GetName(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'name', $id, 'id', $public);
    }


    /**
     * 順番を取得
     *
     * @param string $id
     * @param boolean $public
     * @return string|null
     */
    public function GetSortId(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'sort_id', $id, 'id', $public);
    }


    /**
     * 名前からIDを取得
     *
     * @param string $name     * @param boolean $public
     * @return string|null
     */
    public function GetIdFromName(string $name, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $name, 'name', $public);
    }


    /**
     * 順番からIDを取得
     *
     * @param string $sort_id     * @param boolean $public
     * @return string|null
     */
    public function GetIdFromSortId(string $sort_id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $sort_id, 'sort_id', $public);
    }


    /**
     * IDの登録有無
     *
     * @param string $id
     * @param boolean $public
     * @return boolean
     */
    public function IdExists(string $id, bool $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $id, 'id', $public);
    }


    /**
     * 名前の登録有無
     *
     * @param string $name     * @param boolean $public
     * @return boolean
     */
    public function NameExists($name, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $name, 'name', $public);
    }


    /**
     * 順番の登録有無
     *
     * @param string $sort_id     * @param boolean $public
     * @return boolean
     */
    public function SortIdExists($sort_id, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $sort_id, 'sort_id', $public);
    }


    /**
     * 名前が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $name：対象名前     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function NameSameExists($name, $id = '', $public = false) : ?bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $name, 'name', $id, 'id', $public);
    }


    /**
     * 順番が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sort_id：対象順番     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function SortIdSameExists($sort_id, $id = '', $public = false) : ?bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sort_id, 'sort_id', $id, 'id', $public);
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


    /**
     * DB登録処理
     *
     * @param array|null $registData：登録内容（連想配列[key : 対象カラム, value : 値]）
     * @param string $id：登録対象ID
     * @return string|null
     */
    public function Regist(?array $registData = '', string $id = '') : ?string
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


    /**
     * DB削除処理
     *
     * @param string $id：対象ID
     * @return boolean|null
     */
    public function Delete(string $id) : ?bool
    {
        // 対象IDが登録されているか
        if ($this->IdExists($id, true)) {
            // 削除処理
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
        $returnVal[self::ID_STATUS_OK] = self::NAME_STATUS_OK;
        $returnVal[self::ID_STATUS_NG] = self::NAME_STATUS_NG;

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
