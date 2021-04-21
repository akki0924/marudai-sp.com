\<\?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * <?= $name ?>データ用ライブラリー
 *
 * <?= $name ?>データの取得および処理する為の関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     <?= date('Y/m/d') ?>：新規作成
 */
class <?= $fileName ?>
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = '<?= $tableName ?>';                  // マスタテーブル
<?php
    foreach ($constList as $key => $val) {
        ?>
    // <?= $val['comment']; ?>

<?php
        for ($i = 0, $n = count($val['data']); $i < $n; $i ++) {
            ?>
    const ID_<?= $key ?>_<?= $val['data'][$i]['key'] ?> = '<?= $val['data'][$i]['id'] ?>';
<?php
        }
        for ($i = 0, $n = count($val['data']); $i < $n; $i ++) {
            ?>
    const NAME_<?= $key ?>_<?= $val['data'][$i]['key'] ?> = '<?= $val['data'][$i]['name'] ?>';
<?php
        }
    }
?>

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
<?php for ($i = 0, $n = count($columnList); $i < $n; $i ++) { ?>
                " . self::MASTER_TABLE . " . <?= $columnList[$i] ?><?= ($i < ($n - 1) ? ',' : '') ?>

<?php } ?>
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


<?php for ($i = 0, $n = count($selectList); $i < $n; $i ++) { ?>
    /**
     * <?= $selectList[$i]['title'] ?>を取得
     *
     * @param string $id
     * @param boolean $public
     * @return string|null
     */
    public function Get<?= $selectList[$i]['key'] ?>(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, '<?= $selectList[$i]['name'] ?>', $id, 'id', $public);
    }


<?php } ?>
<?php for ($i = 0, $n = count($selectList); $i < $n; $i ++) { ?>
    /**
     * <?= $selectList[$i]['title'] ?>からIDを取得
     *
     * @param string $<?= $selectList[$i]['name'] ?>
     * @param boolean $public
     * @return string|null
     */
    public function GetIdFrom<?= $selectList[$i]['key'] ?>(string $<?= $selectList[$i]['name'] ?>, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $<?= $selectList[$i]['name'] ?>, '<?= $selectList[$i]['name'] ?>', $public);
    }


<?php } ?>
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


<?php for ($i = 0, $n = count($selectList); $i < $n; $i ++) { ?>
    /**
     * <?= $selectList[$i]['title'] ?>の登録有無
     *
     * @param string $<?= $selectList[$i]['name'] ?>
     * @param boolean $public
     * @return boolean
     */
    public function <?= $selectList[$i]['key'] ?>Exists($<?= $selectList[$i]['name'] ?>, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $<?= $selectList[$i]['name'] ?>, '<?= $selectList[$i]['name'] ?>', $public);
    }


<?php } ?>
<?php for ($i = 0, $n = count($selectList); $i < $n; $i ++) { ?>
    /**
     * <?= $selectList[$i]['title'] ?>が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $<?= $selectList[$i]['name'] ?>：対象<?= $selectList[$i]['title'] ?>
     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function <?= $selectList[$i]['key'] ?>SameExists($<?= $selectList[$i]['name'] ?>, $id = '', $public = false) : ?bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $<?= $selectList[$i]['name'] ?>, '<?= $selectList[$i]['name'] ?>', $id, 'id', $public);
    }


<?php } ?>

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


<?php
    foreach ($constList as $key => $val) {
        ?>
    /**
     * <?= $val['comment'] ?>一覧を配列形式で取得
     *
     * @return array
     */
    public function Get<?= $val['key'] ?>List() : array
    {
<?php
    for ($i = 0, $n = count($val['data']); $i < $n; $i ++) {
        ?>
        $returnVal[self::ID_<?= $key ?>_<?= $val['data'][$i]['key'] ?>] = self::NAME_<?= $key ?>_<?= $val['data'][$i]['key'] ?>;
<?php
    } ?>

        return $returnVal;
    }
    /**
     * <?= $val['comment'] ?>名を取得
     *
     * @param string $id
     * @return string
     */
    public function Get<?= $val['key'] ?>Name($id) : string
    {
        // 一覧リストを取得
        $list = $this->Get<?= $val['key'] ?>List();
        return (isset($list[ $id ]) ? $list[ $id ] : '');
    }
    /**
     * <?= $val['comment'] ?>の存在確認結果を取得
     *
     * @param string $id
     * @return bool
     */
    public function Get<?= $val['key'] ?>Exists($id) : bool
    {
        // 一覧リストを取得
        $list = $this->Get<?= $val['key'] ?>List();
        return (isset($list[ $id ]) ? true : false);
    }


<?php
    }
?>
}
