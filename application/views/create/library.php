\<\?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * <?= $title ?>

 *
 * <?= $description ?>

 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     <?= date('Y/m/d') ?>：新規作成
 */
class <?= $className ?> extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const MASTER_TABLE = '<?= $tableName ?>';
<?php for ($i = 0, $n = count($constOnly); $i < $n; $i ++) { ?>
    // <?= $constOnly[$i]['title']; ?>

    const <?= $constOnly[$i]['key']; ?> = <?= !is_numeric($constOnly[$i]['val']) ? "'" . $constOnly[$i]['val'] . "'" : $constOnly[$i]['val'] ?>;
<?php } ?>
<?php
    foreach ($constSet as $key => $val) {
        ?>
    // <?= $val['title']; ?>

<?php
        for ($i = 0, $n = count($val['data']); $i < $n; $i ++) {
            ?>
    const ID_<?= $key ?>_<?= $val['data'][$i]['key'] ?> = <?= !is_numeric($val['data'][$i]['val']) ? "'" . $val['data'][$i]['val'] . "'" : $val['data'][$i]['val'] ?>;
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


<?php for ($i = 0, $n = count($detailList); $i < $n; $i ++) { ?>
    /**
     * <?= $detailList[$i]['description'] ?>

     *
<?php for ($arg_i = 0, $arg_n = count($detailList[$i]['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
     * @param <?= $detailList[$i]['arg'][$arg_i]['type'] ?> <?= $detailList[$i]['arg'][$arg_i]['key'] ?>：<?= $detailList[$i]['arg'][$arg_i]['title'] ?>

<?php } ?>
     * @return <?= $detailList[$i]['returnType'] ?>|null
     */
    public function <?= $detailList[$i]['key'] ?> (<?php
    for ($arg_i = 0, $arg_n = count($detailList[$i]['arg']); $arg_i < $arg_n; $arg_i ++) {
        echo $detailList[$i]['arg'][$arg_i]['type'] . ' ';
        echo $detailList[$i]['arg'][$arg_i]['key'];
        echo($detailList[$i]['arg'][$arg_i]['default'] ? ' = ' . $detailList[$i]['arg'][$arg_i]['default'] : '');
        echo($arg_i < ($arg_n - 1) ? ', ' : ''); ?>
<?php
    }
?>) : ?<?= $detailList[$i]['returnType'] ?>

    {
        // 返値を初期化
<?php if ($detailList[$i]['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($detailList[$i]['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($detailList[$i]['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
        // SQL
        $query = $this->CI->db->query("
            SELECT
<?php for ($column_i = 0, $column_n = count($detailList[$i]['column']); $column_i < $column_n; $column_i ++) { ?>
                " . self::MASTER_TABLE . " . <?= $detailList[$i]['column'][$column_i] ?><?= ($column_i < ($column_n - 1) ? ',' : '') ?>

<?php } ?>
            FROM " . self::MASTER_TABLE . "
<?php if ($detailList[$i]['arg']) { ?>
            WHERE (
<?php for ($arg_i = 0, $arg_n = count($detailList[$i]['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
<?php if ($detailList[$i]['arg'][$arg_i]['key'] != '$public' && $detailList[$i]['arg'][$arg_i]['key'] != '') { ?>
                " . self::MASTER_TABLE . " . <?= $detailList[$i]['arg'][$arg_i]['column'] ?> = " . $this->CI->db_lib->SetWhereVar(<?= $detailList[$i]['arg'][$arg_i]['key'] ?>) . "<?= (($arg_n > ($arg_i + 1)) && $detailList[$i]['arg'][($arg_i + 1)]['key'] != '$public' ? ' AND' : '') ?>

<?php } else { ?>
                " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base_lib::STATUS_ENABLE : "") . "
<?php } ?>
<?php } ?>
            )
<?php } ?>
            ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
<?php if ($detailList[$i]['single']) { ?>
            $resultList = $query->result_array();
            foreach ($resultList[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
<?php } else { ?>
            $returnVal = $query->result_array();
<?php } ?>
        }
        return $returnVal;
    }


<?php } ?>
<?php for ($i = 0, $n = count($selectList); $i < $n; $i ++) { ?>
    /**
     * <?= $selectList[$i]['title'] ?>一覧を取得
     *
     * @param bool $public
     * @return array|null
     */
    public function Get<?= $selectList[$i]['key'] ?>List(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues(self::MASTER_TABLE, '<?= $selectList[$i]['column'] ?>', $public);
    }


<?php } ?>
<?php for ($i = 0, $n = count($choiceList); $i < $n; $i ++) { ?>
    /**
     * <?= $choiceList[$i]['title'] ?>を取得
     *
     * @param string $id
     * @param boolean $public
     * @return string|null
     */
    public function Get<?= $choiceList[$i]['key'] ?>(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, '<?= $choiceList[$i]['column'] ?>', $id, 'id', $public);
    }


<?php } ?>
<?php for ($i = 0, $n = count($choiceList); $i < $n; $i ++) { ?>
    /**
     * <?= $choiceList[$i]['title'] ?>からIDを取得
     *
     * @param string $<?= $choiceList[$i]['column'] ?>

     * @param boolean $public
     * @return string|null
     */
    public function GetIdFrom<?= $choiceList[$i]['key'] ?>(string $<?= $choiceList[$i]['column'] ?>, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'id', $<?= $choiceList[$i]['column'] ?>, '<?= $choiceList[$i]['column'] ?>', $public);
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


<?php for ($i = 0, $n = count($choiceList); $i < $n; $i ++) { ?>
    /**
     * <?= $choiceList[$i]['title'] ?>の登録有無
     *
     * @param string $<?= $choiceList[$i]['column'] ?>

     * @param boolean $public
     * @return boolean
     */
    public function <?= $choiceList[$i]['key'] ?>Exists($<?= $choiceList[$i]['column'] ?>, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $<?= $choiceList[$i]['column'] ?>, '<?= $choiceList[$i]['column'] ?>', $public);
    }


<?php } ?>
<?php for ($i = 0, $n = count($choiceList); $i < $n; $i ++) { ?>
    /**
     * <?= $choiceList[$i]['title'] ?>が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $<?= $choiceList[$i]['column'] ?>：対象<?= $choiceList[$i]['title'] ?>

     * @param string $id：除外ID
     * @param boolean $public
     * @return boolean
     */
    public function <?= $choiceList[$i]['key'] ?>SameExists($<?= $choiceList[$i]['column'] ?>, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $<?= $choiceList[$i]['column'] ?>, '<?= $choiceList[$i]['column'] ?>', $id, 'id', $public);
    }


<?php } ?>
<?php if ($CreateId_flg) { ?>
    /**
     * 新規IDを生成
     *
     * @param boolean $public
     * @return string
     */
    public function CreateId(bool $public = false) : string
    {
        // 未登録のランダム文字列を生成
        return $this->CI->db_lib->CreateStr(self::MASTER_TABLE, 'id', self::ID_STR_NUM, $public);
    }


<?php } ?>
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
        // 対象IDが登録されているか
        if ($this->IdExists($id, true)) {
            // 削除処理
            return $this->CI->db_lib->Delete(self::MASTER_TABLE, true, $id);
        }
    }


<?php
    foreach ($constSet as $key => $val) {
        ?>
    /**
     * <?= $val['title'] ?>一覧を配列形式で取得
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
     * <?= $val['title'] ?>名を取得
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
     * <?= $val['title'] ?>の存在確認結果を取得
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
