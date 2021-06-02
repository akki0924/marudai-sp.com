\<\?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * <?= $comment ?>用ライブラリー
 *
 * <?= $comment ?>データの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     <?= date('Y/m/d') ?>：新規作成
 */
class <?= ucfirst($targetName) ?>_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = '<?= $tableName ?>';
<?php for ($i = 0, $n = count($table); $i < $n; $i ++) { ?>
<?php if ($table[$i]['name'] == 'status') { ?>
    // 表示ステータス
    const ID_STATUS_ENABLE = 1;
    const ID_STATUS_DISABLE = -1;
    const NAME_STATUS_ENABLE = '表示';
    const NAME_STATUS_DISABLE = '非表示';
<?php } ?>
<?php } ?>
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
<?php for ($i = 0, $n = count($table); $i < $n; $i ++) { ?>
                " . self::MASTER_TABLE . " . <?= $table[$i]['name'] ?>,
<?php if ($table[$i]['name'] == 'status') { ?>
                CASE " . self::MASTER_TABLE . " . status
                    WHEN " . self::ID_STATUS_ENABLE . " THEN '" . self::NAME_STATUS_ENABLE . "'
                    ELSE '" . self::NAME_STATUS_DISABLE . "'
                END AS status_name,
<?php } ?>
<?php if ($table[$i]['name'] == 'regist_date') { ?>
                DATE_FORMAT(" . self::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
<?php } ?>
<?php if ($table[$i]['name'] == 'edit_date') { ?>
                DATE_FORMAT(" . self::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
<?php } ?>
<?php } ?>
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


<?php for ($i = 0, $n = count($table); $i < $n; $i ++) { ?>
    /**
     * 内容一覧を取得
     *
     * @param bool $public
     * @return array|null
     */
    public function GetContentsList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues(self::MASTER_TABLE, 'contents', $public);
    }


<?php } ?>

    /**
     * 内容を取得
     *
     * @param string $id
     * @param boolean $public
     * @return string|null
     */
    public function GetContents(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'contents', $id, 'id', $public);
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
     * 内容からIDを取得
     *
     * @param string $contents
     * @param boolean $public
     * @return string|null
     */
    public function GetIdFromContents(string $contents, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $contents, 'contents', $public);
    }


    /**
     * 順番からIDを取得
     *
     * @param string $sort_id
     * @param boolean $public
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
     * 内容の登録有無
     *
     * @param string $contents
     * @param boolean $public
     * @return boolean
     */
    public function ContentsExists($contents, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $contents, 'contents', $public);
    }


    /**
     * 順番の登録有無
     *
     * @param string $sort_id
     * @param boolean $public
     * @return boolean
     */
    public function SortIdExists($sort_id, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $sort_id, 'sort_id', $public);
    }


    /**
     * 内容が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $contents：対象内容
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function ContentsSameExists($contents, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $contents, 'contents', $id, 'id', $public);
    }


    /**
     * 順番が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sort_id：対象順番
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function SortIdSameExists($sort_id, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $sort_id, 'sort_id', $id, 'id', $public);
    }


    /**
     * DB登録処理
     *
     * @param array|null $registData：登録内容（連想配列[key : 対象カラム, value : 値]）
     * @param string $id：登録対象ID
     * @return string|null
     */
    public function Regist(?array $registData = array(), string $id = '') : ?string
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
        // 返り値をセット
        $returnVal = false;
        // 対象IDが登録されているか
        if ($this->IdExists($id, true)) {
            // 削除処理
            $returnVal = $this->CI->db_lib->Delete(self::MASTER_TABLE, true, $id);
        }
        return $returnVal;
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
