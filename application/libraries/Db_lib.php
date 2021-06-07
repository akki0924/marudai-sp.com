<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能 : データベース用サポート処理ライブラリー
    ■概　要 : 登録、更新、削除などのDB登録関数群
    ■更新日 : 2021/04/23
    ■担　当 : crew.miwa

    ■更新履歴：
        2018/01/19 : 作成開始
        2018/10/26 : 各メソッド名を変更
        2020/01/31 : コメント欄を更新
        2021/04/23
    */

class Db_lib
{
    // 定数を宣言
    const CREATE_STRING_NUM = 10;
    const CREATE_NUMBER_NUM = 10;
    const DEFAULT_SORT_ID = 1;
    const DEFAULT_SORT_COLUMN = 'sort_id';

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
        関数名 : GetColumnValues
        概　要 : 対象カラムデータ一覧を取得
        引　数 : $tableName : テーブル名
                $columnSql : 取得カラム（配列形式）
                $whereSql : WHERE情報（配列形式）
                $orderSql : ORDER情報（配列形式）
                $singleFlg : 単独データフラグ
    */
    public function GetColumnValues($tableName, $columnSql = '', $whereSql = '', $orderSql = '', $singleFlg = false)
    {
        // カラム情報セット確認
        if (is_array($columnSql)) {
            // WHERE情報を再セット
            if (! is_array($whereSql)) {
                $whereSql = [];
            }
            // ORDER情報を再セット
            if (! is_array($orderSql)) {
                $orderSql = array();
                // ソートキーをセット
                if ($this->ColumnExists($tableName, self::DEFAULT_SORT_COLUMN)) {
                    $orderSql[] = self::DEFAULT_SORT_COLUMN . " ASC";
                }
            }

            $query = $this->CI->db->query("
                SELECT
                    " . (isset($columnSql) && count($columnSql) > 0 ? @implode(",", $columnSql)  : "") . "
                FROM " . Base_lib::AddSlashes($tableName) . "
                " . (count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
                " . (is_array($orderSql) && count($orderSql) > 0 ? ("ORDER BY " . @implode(" , ", $orderSql)) : "") . "
                " . ($singleFlg ? 'LIMIT 0, 1' : '') . "
                ;
            ");
            // 結果が、空でない場合
            if ($query->num_rows() > 0) {
                if (! $singleFlg) {
                    // 結果を配列でセット
                    $returnVal = $query->result_array();
                } else {
                    // 結果を配列でセット
                    $list = $query->result_array();
                    // 最初の一覧のみセット
                    $returnVal = $list[0];
                }
            } else {
                $returnVal = false;
            }
            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : GetSelectValues
        概　要 : データ一覧を取得
        引　数 : $tableName : テーブル名
                $targetKey : 対象の値のカラム名
                $public : ステータスフラグ
    */
    public function GetSelectValues($tableName, $targetKey = 'name', $public = false)
    {
        // ソートキーをセット
        if ($this->ColumnExists($tableName, self::DEFAULT_SORT_COLUMN)) {
            $sortKey = self::DEFAULT_SORT_COLUMN;
        } else {
            $sortKey = $targetKey;
        }

        $query = $this->CI->db->query("
            SELECT
                id,
                " . Base_lib::AddSlashes($targetKey) . "
            FROM " . Base_lib::AddSlashes($tableName) . "
            " . ($public ? "WHERE status >= " . Base_lib::STATUS_ENABLE : "") . "
            ORDER BY " . Base_lib::AddSlashes($sortKey) . " ASC;
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // CordIgniter用配列にセット
                $returnVal[$row->id] = $row->{$targetKey};
            }
        } else {
            $returnVal = "";
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetValue
        概　要 : 対象の値を取得
        引　数 : $tableName : テーブル名
                $columnName : 対象のカラム名
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
                $public : ステータスフラグ
    */
    public function GetValue($tableName, $columnName, $whereVal, $whereKey = 'id', $public = false)
    {
        // WHERE情報を初期化
        $whereSql = [];
        // WHERE対象の値が配列
        if (is_array($whereVal) && count($whereVal) > 0) {
            $columnName = "GROUP_CONCAT(" . $columnName . " separator '" . Base_lib::STR_DELIMITER_DISPLAY . "')";
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " IN ('" . @implode("','", $whereVal) . "')";
        } else {
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " = '" . Base_lib::AddSlashes($whereVal) . "'";
        }
        // ステータス情報
        if ($public) {
            $whereSql[] = "status >= " . Base_lib::STATUS_ENABLE;
        }

        $query = $this->CI->db->query("
            SELECT " . $columnName . "
            FROM " . Base_lib::AddSlashes($tableName) . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            if ($query->num_rows() > 1) {
                $getList = $query->result_array();
                $returnVal = '';
                for ($i = 0, $n = count($getList); $i < $n; $i ++) {
                    if ($i > 0) {
                        $returnVal .= '、';
                    }
                    $returnVal .= $getList[$i][$columnName];
                }
                return $returnVal;
            } else {
                foreach ($query->result() as $row) {
                    // 最初に取得した値を返り値としてはき出す
                    return $row->{$columnName};
                }
            }
        }
    }
    /*====================================================================
        関数名 : GetValueLatest
        概　要 : 対象の値を取得
        引　数 : $tableName : テーブル名
                $columnName : 対象のカラム名
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
                $public : ステータスフラグ
                $orderKey : ORDER対象の値のカラム名
    */
    public function GetValueLatest($tableName, $columnName, $whereVal, $whereKey = 'id', $public = false, $orderKey = 'regist_date')
    {
        // WHERE情報を初期化
        $whereSql = [];
        // WHERE対象の値が配列
        if (is_array($whereVal) && count($whereVal) > 0) {
            $columnName = "GROUP_CONCAT(" . $columnName . " separator '" . Base_lib::STR_DELIMITER_DISPLAY . "')";
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " IN ('" . @implode("','", $whereVal) . "')";
        } else {
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " = '" . Base_lib::AddSlashes($whereVal) . "'";
        }
        // ステータス情報
        if ($public) {
            $whereSql[] = "status >= " . Base_lib::STATUS_ENABLE;
        }

        $query = $this->CI->db->query("
            SELECT " . $columnName . "
            FROM " . Base_lib::AddSlashes($tableName) . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            ORDER BY " .  Base_lib::AddSlashes($orderKey) . " DESC
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return $row->{$columnName};
            }
        }
    }
    /*====================================================================
        関数名 : GetValueMax
        概　要 : 取得最大値を返す
        引　数 : $tableName : テーブル名
                $columnName : 対象のカラム名
                $public : ステータスフラグ
    */
    public function GetValueMax($tableName, $columnName = self::DEFAULT_SORT_COLUMN, $public = false)
    {
        /*
                $query = $this->CI->db->query("
                    SELECT MAX(" . Base_lib::AddSlashes( $columnName ) . ") AS sort_id_max
                    FROM " . Base_lib::AddSlashes( $tableName ) . "
                    " . ($public ? " WHERE status >= " . Base_lib::STATUS_ENABLE : "") . "
                ");
                // 結果が、空でない場合
                if ($query->num_rows() > 0) {
                    foreach ( $query->result() AS $row ) {
                        // 最初に取得した値を返り値としてはき出す
                        return $row->sort_id_max;
                    }
                }
        */
        return $this->GetSelectValueMax($tableName, $columnName, "", "", $public);
    }
    /*====================================================================
        関数名 : GetSelectValueMax
        概　要 : 取得最大値を返す
        引　数 : $tableName : テーブル名
                $columnName : 対象のカラム名
                $selectKey : 厳選カラム名
                $sekectId : 厳選カラムID
                $public : ステータスフラグ
    */
    public function GetSelectValueMax(
        $tableName,
        $columnName = self::DEFAULT_SORT_COLUMN,
        $selectKey = "",
        $sekectId = "",
        $public = false
    ) {
        if ($selectKey != '' && $sekectId != '') {
            $whereSql[] = $selectKey = "'" . Base_lib::AddSlashes($sekectId) . "'";
        }
        if ($public) {
            $whereSql[] = "status >= " . Base_lib::STATUS_ENABLE;
        }

        $query = $this->CI->db->query("
            SELECT MAX(" . Base_lib::AddSlashes($columnName) . ") AS sort_id_max
            FROM " . Base_lib::AddSlashes($tableName) . "
            " . (count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return $row->sort_id_max;
            }
        }
    }
    /*====================================================================
        関数名 : GetCount
        概　要 : 取得合計数を返す
        引　数 : $tableName : テーブル名
                $whereSql : WHERE文（配列）
                $public : ステータスフラグ
    */
    public function GetCount($tableName, $whereSql = "", $public = false)
    {
        // 初期値をセット
        $returnVal = 0;

        if ($public) {
            $whereSql[] = Base_lib::AddSlashes($tableName) . " . status >= " . Base_lib::STATUS_ENABLE;
        }
        // SQLクエリ
        $query = $this->CI->db->query("
            SELECT COUNT(*) AS count
            FROM " . Base_lib::AddSlashes($tableName) . "
            ".(is_array($whereSql) && count($whereSql) > 0 ? " WHERE ( ".@implode(" AND ", $whereSql)." ) " : "")."
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->row()->count;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : ColumnExists
        概　要 : カラムが存在するかどうか
        引　数 : $tableName : テーブル名
                $columnName : 対象のカラム名
    */
    public function ColumnExists($tableName, $columnName)
    {
        $query = $this->CI->db->query("
            SHOW COLUMNS FROM " . Base_lib::AddSlashes($tableName) . " LIKE '" . Base_lib::AddSlashes($columnName) . "'
        ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return ($row->Field != '' ? true : false);
            }
        }
    }
    /*====================================================================
        関数名 : ValueExists
        概　要 : 値が存在するかどうか
        引　数 : $tableName : テーブル名
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
                $public : ステータスフラグ
    */
    public function ValueExists($tableName, $whereVal, $whereKey = 'id', $public = false)
    {
        // WHERE情報を初期化
        $whereSql = [];
        // WHERE対象の値が配列
        if (is_array($whereVal) && count($whereVal) > 0) {
            // WHERE対象の値の配列数
            $whereValCount = count($whereVal);
            $columnName = "GROUP_CONCAT(" . $columnName . " separator '" . Base_lib::STR_DELIMITER_DISPLAY . "')";
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " IN ('" . @implode("','", Base_lib::AddSlashes($whereVal)) . "')";
        } else {
            $whereSql[] = Base_lib::AddSlashes($whereKey) . " = '" . Base_lib::AddSlashes($whereVal) . "'";
        }
        // ステータス情報
        if ($public) {
            $whereSql[] = "status >= " . Base_lib::STATUS_ENABLE;
        }
        $query = $this->CI->db->query("
            SELECT COUNT(" . $whereKey . ") AS count
            FROM " . Base_lib::AddSlashes($tableName) . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
        ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                if (isset($whereValCount)) {
                    // 最初に取得した値を返り値としてはき出す
                    return ($row->count == $whereValCount ? true : false);
                } else {
                    // 最初に取得した値を返り値としてはき出す
                    return ($row->count > 0 ? true : false);
                }
            }
        }
    }
    /*====================================================================
        関数名 : SameExists
        概　要 : 対象ID以外に同じ値が存在するかどうか
        引　数 : $tableName : テーブル名
                $targetVal : WHERE対象の値（同値確認）
                $targetKey : WHERE対象の値のカラム名（同値確認）
                $whereVal : WHERE対象の値（対象キー）
                $whereKey : WHERE対象の値のカラム名（対象キー）
                $public : ステータスフラグ
    */
    public function SameExists($tableName, $targetVal = '', $targetKey = 'name', $whereVal = '', $whereKey = 'id', $public = false)
    {
        $query = $this->CI->db->query("
            SELECT COUNT(*) AS count
            FROM " . Base_lib::AddSlashes($tableName) . "
            WHERE (
                " . ($whereVal != "" ? Base_lib::AddSlashes($whereKey) . " <> '" . Base_lib::AddSlashes($whereVal) . "' AND" : "") . "
                " . Base_lib::AddSlashes($targetKey) . " = '" . Base_lib::AddSlashes($targetVal) . "'
                " . ($public ? " AND status >= " . Base_lib::STATUS_ENABLE : "") . "
            )
        ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // 最初に取得した値を返り値としてはき出す
                return ($row->count > 0 ? true : false);
            }
        }
    }
    /*====================================================================
        関数名 : CreateStr
        概　要 : 未使用の大文字小文字の英数字文字列を生成を取得
        引　数 : $tableName : テーブル名
                $whereKey : WHERE対象の値のカラム名
                $createNum : 生成する文字数
                $public : ステータスフラグ
    */
    public function CreateStr($tableName, $whereKey = 'id', $createNum = "", $public = false)
    {
        // 文字数をセット
        $createNum = ($createNum != "" ? $createNum : self::CREATE_STRING_NUM);

        do {
            // 文字列生成
            $createStr = random_string('alnum', $createNum);
        } while ($this->ValueExists($tableName, $createStr, $whereKey, $public));

        return $createStr;
    }
    /*====================================================================
        関数名 : CreateNum
        概　要 : 未使用の数字文字列を生成を取得
        引　数 : $tableName : テーブル名
                $whereKey : WHERE対象の値のカラム名
                $createNum : 生成する文字数
                $public : ステータスフラグ
    */
    public function CreateNum($tableName, $whereKey = 'id', $createNum = "", $public = false)
    {
        // 文字数をセット
        $createNum = ($createNum != "" ? $createNum : self::CREATE_NUMBER_NUM);

        do {
            // 文字列生成
            $createStr = random_string('numeric', $createNum);
        } while ($this->ValueExists($tableName, $createStr, $whereKey, $public));

        return $createStr;
    }
    /*====================================================================
        関数名 : CreateSortId
        概　要 : 順番の最大値＋１を取得
        引　数 : $tableName : テーブル名
                $columnName : ソート対象カラム名
                $public : ステータスフラグ
    */
    public function CreateSortId($tableName, $columnName = self::DEFAULT_SORT_COLUMN, $public = false)
    {
        /*
                // 順番最大値を取得
                $sortIdMax = $this->GetValueMax ( $tableName, $columnName, $public );
                // 順番最大値が取得できれば、＋１を、取得できなければ初期値を返す
                return ( $sortIdMax ? $sortIdMax + 1 : self::DEFAULT_SORT_ID );
        */
        return $this->CreateSelectSortId($tableName, $columnName, "", "", $public);
    }
    /*====================================================================
        関数名 : CreateSelectSortId
        概　要 : 順番の最大値＋１を取得
        引　数 : $tableName : テーブル名
                $columnName : ソート対象カラム名
                $selectKey : 厳選カラム名
                $sekectId : 厳選カラムID
                $public : ステータスフラグ
    */
    public function CreateSelectSortId(
        $tableName,
        $columnName = self::DEFAULT_SORT_COLUMN,
        $selectKey = "",
        $selectId = "",
        $public = false
    ) {
        // 順番最大値を取得
        $sortIdMax = $this->GetSelectValueMax($tableName, $columnName, $selectKey, $selectId, $public);
        // 順番最大値が取得できれば、＋１を、取得できなければ初期値を返す
        return ($sortIdMax ? $sortIdMax + 1 : self::DEFAULT_SORT_ID);
    }
    /*====================================================================
        関数名 : Insert
        概　要 : DB新規追加
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
    */
    public function Insert($tableName, $values)
    {
        // オブジェクト又は、配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }
            // ステータス
            if (
                $this->ColumnExists($tableName, 'status') &&
                ! isset($values['status'])
            ) {
                // カラムが存在する場合、値をセットする
                $formValues['status'] = Base_lib::STATUS_ENABLE;
            }
            // 順番（カラムが存在する場合、値をセットされていない）
            if (
                $this->ColumnExists($tableName, self::DEFAULT_SORT_COLUMN) &&
                ! isset($formValues[self::DEFAULT_SORT_COLUMN])
            ) {
                // 値をセット
                $formValues['sort_id'] = $this->CreateSortId($tableName, self::DEFAULT_SORT_COLUMN, true);
            }
            // 登録日時
            $formValues['regist_date'] = Base_lib::NowDateTime();
            // 更新日時
            $formValues['edit_date'] = Base_lib::NowDateTime();

            // トランザクション開始
            $this->CI->db->trans_start();
            // 新規登録処理
            $returnVal = $this->CI->db->insert($tableName, $formValues);
            // トランザクション終了
            $this->CI->db->trans_complete();

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : Update
        概　要 : DB更新
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
    */
    public function Update($tableName, $values, $whereVal, $whereKey = 'id')
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }
            // 更新日時
            $formValues['edit_date'] = Base_lib::NowDateTime();

            // トランザクション開始
            $this->CI->db->trans_start();
            // WHERE情報をセット
            $this->CI->db->where($whereKey, $whereVal);
            // 更新処理
            $returnVal = $this->CI->db->update($tableName, $formValues);
            // トランザクション終了
            $this->CI->db->trans_complete();

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : InsertNoTrans
        概　要 : DB新規追加（トランザクションしない）
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
    */
    public function InsertNoTrans($tableName, $values)
    {
        // オブジェクト又は、配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }
            // ステータス
            if (
                $this->ColumnExists($tableName, 'status') &&
                ! isset($values['status'])
            ) {
                // カラムが存在する場合、値をセットする
                $formValues['status'] = Base_lib::STATUS_ENABLE;
            }
            // 順番（カラムが存在する場合、値をセットされていない）
            if (
                $this->ColumnExists($tableName, self::DEFAULT_SORT_COLUMN) &&
                ! isset($formValues[self::DEFAULT_SORT_COLUMN])
            ) {
                // 値をセット
                $formValues['sort_id'] = $this->CreateSortId($tableName, self::DEFAULT_SORT_COLUMN, true);
            }
            // 登録日時
            $formValues['regist_date'] = Base_lib::NowDateTime();
            // 更新日時
            $formValues['edit_date'] = Base_lib::NowDateTime();

            // 新規登録処理
            $returnVal = $this->CI->db->insert($tableName, $formValues);

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : UpdateNoTrans
        概　要 : DB更新（トランザクションしない）
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
    */
    public function UpdateNoTrans($tableName, $values, $whereVal, $whereKey = 'id')
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }
            // 更新日時
            $formValues['edit_date'] = Base_lib::NowDateTime();

            // WHERE情報をセット
            $this->CI->db->where($whereKey, $whereVal);
            // 更新処理
            $returnVal = $this->CI->db->update($tableName, $formValues);

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : UpdateNoEscape
        概　要 : DB更新（エスケープ回避）
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
    */
    public function UpdateNoEscape($tableName, $values, $whereVal, $whereKey = 'id')
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $this->CI->db->set($valuesKey, $valuesVal, false);
            }
            // 更新日時
            $this->CI->db->set('edit_date', Base_lib::NowDateTime(), true);

            // トランザクション開始
            $this->CI->db->trans_start();
            // WHERE情報をセット
            if (is_array($whereVal)) {
                for ($i = 0, $n = count($whereKey); $i < $n; $i ++) {
                    $where[$whereKey[$i]] = $whereVal[$i];
                }
            } else {
                $where[$whereKey] = $whereVal;
            }
            $this->CI->db->where($where);
            // 更新処理
            $returnVal = $this->CI->db->update($tableName);
            // トランザクション終了
            $this->CI->db->trans_complete();

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : InsertNoEditDate
        概　要 : DB新規追加
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
    */
    public function InsertNoEditDate($tableName, $values)
    {
        // オブジェクト又は、配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }
            // ステータス
            if (
                $this->ColumnExists($tableName, 'status') &&
                ! isset($values['status'])
            ) {
                // カラムが存在する場合、値をセットする
                $formValues['status'] = Base_lib::STATUS_ENABLE;
            }
            // 順番（カラムが存在する場合、値をセットされていない）
            if (
                $this->ColumnExists($tableName, self::DEFAULT_SORT_COLUMN) &&
                ! isset($formValues[self::DEFAULT_SORT_COLUMN])
            ) {
                // 値をセット
                $formValues['sort_id'] = $this->CreateSortId($tableName, self::DEFAULT_SORT_COLUMN, true);
            }
            // 登録日時
            $formValues['regist_date'] = Base_lib::NowDateTime();

            // トランザクション開始
            $this->CI->db->trans_start();
            // 新規登録処理
            $returnVal = $this->CI->db->insert($tableName, $formValues);
            // トランザクション終了
            $this->CI->db->trans_complete();

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : UpdateNoEditDate
        概　要 : DB更新
        引　数 : $tableName : テーブル名
                $values : 更新情報（オブジェクトまたは配列）
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
    */
    public function UpdateNoEditDate($tableName, $values, $whereVal, $whereKey = 'id')
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object($values) ||
            is_array($values)
        ) {
            // 登録情報をセット
            foreach ($values as $valuesKey => $valuesVal) {
                $formValues[$valuesKey] = $valuesVal;
            }

            // トランザクション開始
            $this->CI->db->trans_start();
            // WHERE情報をセット
            $this->CI->db->where($whereKey, $whereVal);
            // 更新処理
            $returnVal = $this->CI->db->update($tableName, $formValues);
            // トランザクション終了
            $this->CI->db->trans_complete();

            return $returnVal;
        }
    }
    /*====================================================================
        関数名 : Delete
        概　要 : DB削除
        引　数 : $tableName : テーブル名
                $logic : 論理削除フラグ
                $whereVal : WHERE対象の値
                $whereKey : WHERE対象の値のカラム名
    */
    public function Delete($tableName, $logic = true, $whereVal, $whereKey = 'id')
    {
        // 削除対象の値がセットされている場合
        if ($whereVal != '') {
            // 論理削除
            if ($logic) {
                // 更新情報をセット
                $formValues['status'] = Base_lib::STATUS_DISABLE;

                // トランザクション開始
                $this->CI->db->trans_start();
                // WHERE情報をセット
                $this->CI->db->where($whereKey, $whereVal);
                // 削除処理（論理）
                $returnVal = $this->CI->db->update($tableName, $formValues);
                // トランザクション終了
                $this->CI->db->trans_complete();

                return $returnVal;
            }
            // 物理削除
            else {
                // トランザクション開始
                $this->CI->db->trans_start();
                // WHERE情報をセット
                $this->CI->db->where($whereKey, $whereVal);
                // 削除処理
                $returnVal = $this->CI->db->delete($tableName);
                // トランザクション終了
                $this->CI->db->trans_complete();

                return $returnVal;
            }
        }
    }
    /*====================================================================
        関数名 : SetWhereVar
        概　要 : 対象文字列をスラッシュでクォート処理し、型に合った形式にして返す
        引　数 : $varStr : 対象文字列
    */
    public function SetWhereVar($varStr = '')
    {
        // 返値を初期化
        $returnVal = '';
        // 数値以外
        if (!is_numeric($varStr)) {
            // クォート処理し、形式変更
            $returnVal = "'" . Base_lib::AddSlashes($varStr) . "'";
        }
        // 数値
        else {
            // クォート処理
            $returnVal = Base_lib::EmptyToNull($varStr);
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetTables
        概　要 : 対象テーブル一覧を取得
        引　数 : $dbName : DB名
    */
    public function GetTables(?string $dbName = '') : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // DB名を再セット
        $dbName = ($dbName ? $dbName : $this->CI->db->database);
        // SQLを実行
        $query = $this->CI->db->query("
            SHOW TABLES FROM " . Base_lib::AddSlashes($dbName) . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                // 不要な配列情報を除外して取得
                $returnVal[] = $row['Tables_in_' . $this->CI->db->database];
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetTablesData
        概　要 : 対象テーブル一覧を取得
        引　数 : $dbName : DB名
    */
    public function GetTablesData(?string $dbName = '') : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // DB名を再セット
        $dbName = ($dbName ? $dbName : $this->CI->db->database);
        // SQLを実行
        $query = $this->CI->db->query("
            SELECT
                table_name AS name,
                table_comment AS comment
            FROM information_schema.tables
            WHERE table_schema = '" . Base_lib::AddSlashes($dbName) . "';
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetColumns
        概　要 : 対象カラム一覧を取得
        引　数 : $tableName : テーブル名
    */
    public function GetColumns(?string $tableName) : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // SQLを実行
        $query = $this->CI->db->query("
            SHOW COLUMNS FROM " . Base_lib::AddSlashes($tableName) . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
//            $returnVal = $query->result_array();
            foreach ($query->result_array() as $row) {
                // 不要な配列情報を除外して取得
                $returnVal[] = $row['Field'];
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetTableComment
        概　要 : 対象テーブルコメントを取得
        引　数 : $tableName : テーブル名
    */
    public function GetTableComment(?string $tableName) : ?string
    {
        // 返値を初期化
        $returnVal = '';
        // SQLを実行
        $query = $this->CI->db->query("
            SELECT
                table_comment
            FROM INFORMATION_SCHEMA.TABLES
            WHERE (
                TABLE_SCHEMA = '" . $this->CI->db->database . "' AND
                TABLE_NAME = '" . Base_lib::AddSlashes($tableName) . "'
            );
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $resultList = $query->result_array();
            if (isset($resultList[0]['table_comment'])) {
                $returnVal = $resultList[0]['table_comment'];
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetColumnsData
        概　要 : 対象カラムコメントを取得
        引　数 : $tableName : テーブル名
    */
    public function GetColumnsData(?string $tableName) : ?array
    {
        // 返値を初期化
        $returnVal = '';
        // SQLを実行
        $query = $this->CI->db->query("
            SELECT
                COLUMN_NAME AS name,
                COLUMN_COMMENT AS comment,
                DATA_TYPE AS type_simple,
                COLUMN_TYPE AS type,
                EXTRA AS extra
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE (
                TABLE_SCHEMA = '" . $this->CI->db->database . "' AND
                TABLE_NAME = '" . Base_lib::AddSlashes($tableName) . "'
            );
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : CheckAutoIncrement
        概　要 : 対象テーブルカラムコメントを取得
        引　数 : $tableName : テーブル名
    */
    public function CheckAutoIncrement(?string $tableName) : bool
    {
        // 返値を初期化
        $returnVal = false;
        // SQLを実行
        $query = $this->CI->db->query("
            SELECT
                AUTO_INCREMENT
            FROM INFORMATION_SCHEMA.TABLES
            WHERE (
                TABLE_SCHEMA = '" . $this->CI->db->database . "' AND
                TABLE_NAME = '" . Base_lib::AddSlashes($tableName) . "'
            );
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $resultList = $query->result_array();
            if (isset($resultList[0]['AUTO_INCREMENT'])) {
                $returnVal = ($resultList[0]['AUTO_INCREMENT'] > 0 ? true : false);
            }
        }
        return $returnVal;
    }
}
