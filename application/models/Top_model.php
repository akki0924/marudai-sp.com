<?php
/**
 * TOP画面用モデル
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 0.0.1
 * @since 0.0.1     2021/12/22：新規作成
 */
class Top_model extends MY_Model
{
    const DEFAULT_LIST_COUNT = 200;
    const FIRST_MSG = '検索項目を選択してください。';
    const NO_LIST_MSG = '一覧リストが見つかりません。';
    // 並び順用文字列
    const SORT_COLUMN = 'sort_id';
    const SORT_ARROW = 'desc';
    // メールタイトル
    const MAIL_SUBJECT_COMP = 'ママカフェ～ウェルネスフェスタ in Sky Expo～';


    /**
     * コントラクト
     */
    public function __construct()
    {
        // ライブラリー読込み
        $this->load->library(Base_lib::MASTER_DIR . '/measurement_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/outsourcing_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/worker_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/place_lib');
    }


    /**
     * 共通テンプレート
     *
     * @param array $templateVal：テンプレート用配列
     * @return array
     */
    public function sharedTemplate(array $templateVal = array()) : ?array
    {
        // 変数を再セット
        $returnVal = $templateVal;
        // クラス定数をセット
        $returnVal['const'] = $this->worker_lib->GetConstList('worker_lib');

        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog(validation_errors());
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog($_POST);
        Base_lib::ConsoleLog($_FILES);

        return $returnVal;
    }


    /**
     * 入力テンプレート情報を取得
     *
     * @param bool $validFlg：バリデーションフラグ
     * @return array
     */
    public function TopTemplate(bool $validFlg = false) : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // FORM情報
        $id = $this->input->post_get('id', true);
        $action = $this->input->post_get('action', true);
        if ($action == '') {
            // FORM情報をセット
            foreach ($this->FormWorkerList() as $key) {
                $returnVal['form'][$key] = $this->input->post_get($key, true);
            }
        }
        // 遷移アクション時
        else {
            // FORM情報をセット
            foreach ($this->FormWorkerList() as $key) {
                $returnVal['form'][$key] = $this->input->post_get($key, true);
            }
        }
        $whereSqlM = array();
        $whereSqlO = array();
        // 企業名、申請番号検索
        if ($action == 'search1') {
            $startDate = $returnVal['form']['start_y'] . "-";
            $startDate .= $returnVal['form']['start_m'] . "-";
            $startDate .= $returnVal['form']['start_d'];
            $endDate = $returnVal['form']['end_y'] . "-";
            $endDate .= $returnVal['form']['end_m'] . "-";
            $endDate .= $returnVal['form']['end_d'];
            $whereSqlM[] = Measurement_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
            $whereSqlM[] = Measurement_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        }
        // 登録日時検索
        elseif ($action == 'search2') {
            $startDate = $returnVal['form']['start_y'] . "-";
            $startDate .= $returnVal['form']['start_m'] . "-";
            $startDate .= $returnVal['form']['start_d'];
            $endDate = $returnVal['form']['end_y'] . "-";
            $endDate .= $returnVal['form']['end_m'] . "-";
            $endDate .= $returnVal['form']['end_d'];
            $whereSqlO[] = Outsourcing_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
            $whereSqlO[] = Outsourcing_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        }
        // ORDER情報をセット
        $orderSqlM[0]['key'] = Measurement_lib::MASTER_TABLE . ' . regist_date';
        $orderSqlM[0]['arrow'] = 'ASC';
        $orderSqlO[0]['key'] = Outsourcing_lib::MASTER_TABLE . ' . regist_date';
        $orderSqlO[0]['arrow'] = 'ASC';
        // 一覧情報を取得
        $returnVal['list_m'] = $this->GetMeasurementList($whereSqlM, $orderSqlM, null, true);
        $returnVal['list_o'] = $this->GetOutsourcingList($whereSqlO, $orderSqlO, null, true);
        // 選択情報をセット
        $returnVal['select']['year'] = $this->date_lib->GetYearList('年', '2021');
        $returnVal['select']['month'] = $this->date_lib->GetMonthList('月');
        $returnVal['select']['day'] = $this->date_lib->GetDayList('日');

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 入力完了テンプレート情報を取得
     *
     * @return array
     */
    public function CompTemplate()
    {
        return $this->sharedTemplate();
    }


    /**
     * 計量記録一覧を取得
     *
     * @param array $whereSql : WHERE情報(配列形式)
     * @param array $orderSql : ORDER情報(配列→連想形式[key：対象カラム, arrow：矢印])
     * @param array $limitSql : LIMIT情報(連想配列形式[begin：開始行, row：件数])
     * @return array
     */
    public function GetMeasurementList(
        ?array $whereSql = array(),
        ?array $orderSql = array(),
        ?array $limitSql = array()
    ) : ? array {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報を再セット
        if (! is_array($whereSql)) {
            $whereSql = [];
        }
        // ORDER情報を再セット
        if (! is_array($orderSql)) {
            $orderSql = array();
            $orderSql[0]['key'] = Measurement_lib::MASTER_TABLE . ' . regist_date';
            $orderSql[0]['arrow'] = 'DESC';
        }
        // ORDER文を生成
        $orderSqlVal = 'ORDER BY';
        for ($i = 0, $n = count($orderSql); $i < $n; $i ++) {
            $orderSqlVal .= ($i > 0 ? ',' : '');
            $orderSqlVal .= ' ' . $orderSql[$i]['key'] . ' ' . $orderSql[$i]['arrow'];
        }
        // LIMIT文を生成
        if (is_array($limitSql)) {
            $limitSqlVal = 'LIMIT ' . $limitSql['begin'] . ', ' . $limitSql['row'];
        }
        $query = $this->db->query("
            SELECT
                " . Measurement_lib::MASTER_TABLE . " . id,
                DATE_FORMAT(" . Measurement_lib::MASTER_TABLE . " . start_date, '%c/%e') AS start_date_disp,
                CONCAT(" . Place_lib::MASTER_TABLE . " . place, '・', " . Place_lib::MASTER_TABLE . " . scale) AS place_name,
                " . Measurement_lib::MASTER_TABLE . " . code,
                " . Measurement_lib::MASTER_TABLE . " . lot,
                " . Measurement_lib::MASTER_TABLE . " . member_num,
                " . Measurement_lib::MASTER_TABLE . " . packing_num,
                (" . Measurement_lib::MASTER_TABLE . " . member_num * " . Measurement_lib::MASTER_TABLE . " . packing_num) AS total_num,
                " . Measurement_lib::MASTER_TABLE . " . confirm_flg,
                " . Measurement_lib::MASTER_TABLE . " . cleaning_flg,
                " . Measurement_lib::MASTER_TABLE . " . status,
                " . Measurement_lib::MASTER_TABLE . " . regist_date,
                DATE_FORMAT(" . Measurement_lib::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . Measurement_lib::MASTER_TABLE . " . edit_date,
                DATE_FORMAT(" . Measurement_lib::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . Measurement_lib::MASTER_TABLE . "
            LEFT OUTER JOIN " . Place_lib::MASTER_TABLE . " ON " . Measurement_lib::MASTER_TABLE . " . place_id = " . Place_lib::MASTER_TABLE . " . id
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            " . $orderSqlVal . "
            " . (isset($limitSqlVal) ? $limitSqlVal : '') . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
            for ($i = 0, $n = count($returnVal); $i < $n; $i ++) {
                // 作業者名をセット
                $returnVal[$i]['worker_name'] = $this->worker_lib->GetNameL($returnVal[$i]['worker_id']);
            }
        }
        return $returnVal;
    }


    /**
     * 外注依頼一覧を取得
     *
     * @param array $whereSql : WHERE情報(配列形式)
     * @param array $orderSql : ORDER情報(配列→連想形式[key：対象カラム, arrow：矢印])
     * @param array $limitSql : LIMIT情報(連想配列形式[begin：開始行, row：件数])
     * @return array
     */
    public function GetOutsourcingList(
        ?array $whereSql = array(),
        ?array $orderSql = array(),
        ?array $limitSql = array()
    ) : ? array {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報を再セット
        if (! is_array($whereSql)) {
            $whereSql = [];
        }
        // ORDER情報を再セット
        if (! is_array($orderSql)) {
            $orderSql = array();
            $orderSql[0]['key'] = Outsourcing_lib::MASTER_TABLE . ' . regist_date';
            $orderSql[0]['arrow'] = 'DESC';
        }
        // ORDER文を生成
        $orderSqlVal = 'ORDER BY';
        for ($i = 0, $n = count($orderSql); $i < $n; $i ++) {
            $orderSqlVal .= ($i > 0 ? ',' : '');
            $orderSqlVal .= ' ' . $orderSql[$i]['key'] . ' ' . $orderSql[$i]['arrow'];
        }
        // LIMIT文を生成
        if (is_array($limitSql)) {
            $limitSqlVal = 'LIMIT ' . $limitSql['begin'] . ', ' . $limitSql['row'];
        }
        $query = $this->db->query("
            SELECT
                " . Outsourcing_lib::MASTER_TABLE . " . id,
                DATE_FORMAT(" . Outsourcing_lib::MASTER_TABLE . " . start_date, '%c/%e') AS start_date_disp,
                CONCAT(" . Place_lib::MASTER_TABLE . " . place, '・', " . Place_lib::MASTER_TABLE . " . scale) AS place_name,
                " . Outsourcing_lib::MASTER_TABLE . " . code,
                " . Outsourcing_lib::MASTER_TABLE . " . lot,
                " . Outsourcing_lib::MASTER_TABLE . " . f_num,
                " . Outsourcing_lib::MASTER_TABLE . " . packing_num,
                (" . Outsourcing_lib::MASTER_TABLE . " . f_num * " . Outsourcing_lib::MASTER_TABLE . " . packing_num) AS total_num,
                " . Outsourcing_lib::MASTER_TABLE . " . confirm_flg,
                " . Outsourcing_lib::MASTER_TABLE . " . cleaning_flg,
                " . Outsourcing_lib::MASTER_TABLE . " . status,
                " . Outsourcing_lib::MASTER_TABLE . " . regist_date,
                DATE_FORMAT(" . Outsourcing_lib::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . Outsourcing_lib::MASTER_TABLE . " . edit_date,
                DATE_FORMAT(" . Outsourcing_lib::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . Outsourcing_lib::MASTER_TABLE . "
            LEFT OUTER JOIN " . Place_lib::MASTER_TABLE . " ON " . Outsourcing_lib::MASTER_TABLE . " . place_id = " . Place_lib::MASTER_TABLE . " . id
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            " . $orderSqlVal . "
            " . (isset($limitSqlVal) ? $limitSqlVal : '') . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
            for ($i = 0, $n = count($returnVal); $i < $n; $i ++) {
                // 作業者名をセット
                $returnVal[$i]['worker_name'] = $this->worker_lib->GetNameL($returnVal[$i]['worker_id']);
            }
        }
        return $returnVal;
    }


    /**
     * 入力フォーム用配列を取得
     *
     * @return array
     */
    public function FormWorkerList()
    {
        $returnVal = array(
            'name_l',
            'name_f',
        );

        return $returnVal;
    }


    /**
     * 仮登録ページ エラーチェック配列
     *
     * @return array
     */
    public function ConfigValues() : array
    {
        // 姓
        $returnVal[] = array(
            'field'   => 'name_l',
            'label'   => '姓',
            'rules'   => 'required'
        );
        // 名
        $returnVal[] = array(
            'field'   => 'name_f',
            'label'   => '名',
            'rules'   => 'required'
        );
        return $returnVal;
    }
}
