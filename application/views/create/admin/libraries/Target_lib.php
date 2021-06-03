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
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // テーブル名をセット
        $this->SetDbTable('<?= $tableName ?>');
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


<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if (!method_exists('Base_lib', 'Get' . ucfirst($tableSel[$i]['name_camel']) . 'List')) { ?>
    /**
     * <?= $tableSel[$i]['comment'] ?>一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function Get<?= ucfirst($tableSel[$i]['name_camel']) ?>List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), '<?= $tableSel[$i]['name'] ?>', $public);
    }


<?php } ?>
<?php } ?>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if (!method_exists('Base_lib', 'Get' . ucfirst($tableSel[$i]['name_camel']))) { ?>
    /**
     * <?= $tableSel[$i]['comment'] ?>を取得
     *
     * @param string $id：ID
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function Get<?= ucfirst($tableSel[$i]['name_camel']) ?>(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), '<?= $tableSel[$i]['name'] ?>', $id, 'id', $public);
    }


<?php } ?>
<?php } ?>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if (!method_exists('Base_lib', 'GetIdFrom' . ucfirst($tableSel[$i]['name_camel']))) { ?>
    /**
     * <?= $tableSel[$i]['comment'] ?>からIDを取得
     *
     * @param string $<?= $tableSel[$i]['name_camel'] ?>：対象<?= $tableSel[$i]['comment'] ?>

     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFrom<?= ucfirst($tableSel[$i]['name_camel']) ?>(string $<?= $tableSel[$i]['name_camel'] ?>, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $<?= $tableSel[$i]['name_camel'] ?>, '<?= $tableSel[$i]['name'] ?>', $public);
    }


<?php } ?>
<?php } ?>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if (!method_exists('Base_lib', ucfirst($tableSel[$i]['name_camel']) . 'Exists')) { ?>
    /**
     * <?= $tableSel[$i]['comment'] ?>の登録有無
     *
     * @param string $<?= $tableSel[$i]['name_camel'] ?>：対象<?= $tableSel[$i]['comment'] ?>

     * @param boolean $public
     * @return boolean
     */
    public function <?= ucfirst($tableSel[$i]['name_camel']) ?>Exists(string $<?= $tableSel[$i]['name_camel'] ?>, bool $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $<?= $tableSel[$i]['name_camel'] ?>, '<?= $tableSel[$i]['name'] ?>', $public);
    }


<?php } ?>
<?php } ?>
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
<?php if (!method_exists('Base_lib', ucfirst($tableSel[$i]['name_camel']) . 'SameExists')) { ?>
    /**
     * <?= $tableSel[$i]['comment'] ?>が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $<?= $tableSel[$i]['name_camel'] ?>：対象<?= $tableSel[$i]['comment'] ?>

     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function <?= ucfirst($tableSel[$i]['name_camel']) ?>SameExists($<?= $tableSel[$i]['name_camel'] ?>, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $<?= $tableSel[$i]['name_camel'] ?>, '<?= $tableSel[$i]['name'] ?>', $id, 'id', $public);
    }


<?php } ?>
<?php } ?>
}