<?php
/**
 * 作成者追加画面用モデル
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 0.0.1
 * @since 0.0.1     2021/12/22：新規作成
 */
class Keiryo_model extends MY_Model
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
        $returnVal['const'] = $this->measurement_lib->GetConstList('measurement_lib');

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
    public function InputTemplate(bool $validFlg = false) : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // FORM情報
        $id = $this->input->post_get('id', true);
        $action = $this->input->post_get('action', true);
        // FORM情報をセット
        foreach ($this->FormInputList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        foreach ($this->FormSearchList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        // 企業名、申請番号検索
        $startDate = $returnVal['form']['start_y'] . "-";
        $startDate .= $returnVal['form']['start_m'] . "-";
        $startDate .= $returnVal['form']['start_d'];
        $endDate = $returnVal['form']['end_y'] . "-";
        $endDate .= $returnVal['form']['end_m'] . "-";
        $endDate .= $returnVal['form']['end_d'];
        $whereSq[] = Measurement_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
        $whereSq[] = Measurement_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        // ORDER情報をセット
        $orderSql[0]['key'] = Measurement_lib::MASTER_TABLE . ' . regist_date';
        $orderSql[0]['arrow'] = 'ASC';
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList($whereSq, $orderSql, null, true);
        // 選択情報をセット
        $returnVal['select']['confirm_flg'] = $this->measurement_lib->GetConfirmFlgList();
        $returnVal['select']['cleaning_flg'] = $this->measurement_lib->GetCleaningFlgList();
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
     * 登録処理
     *
     * @param bool $validFlg：バリデーションフラグ
     * @return string
     */
    public function RegistAction($validFlg = false) : ?string
    {
        // 返値を初期化
        $returnVal = false;
        // 読み込み時間を延長
        ini_set('max_execution_time', '90');
        // FORM情報
        $id = $this->input->post_get('id', true);
        $action = $this->input->post_get('action', true);
        // 仮登録画面
        if (
            $action == 'add' &&
            $validFlg == true
        ) {
            // FORM情報をセット
            foreach ($this->FormInputList() as $key) {
                // 登録情報にセット
                $form[$key] = $this->input->post_get($key, true);
            }
            // 登録に必要な情報を追加
            $form['status'] = Measurement_lib::ID_STATUS_ENABLE;
            // 登録処理（IDを返す）
            $returnVal = $this->measurement_lib->Regist($form);
        }
        return $returnVal;
    }


    /**
     * 削除処理
     *
     * @return bool
     */
    public function DelAction() : ? bool
    {
        // 返値を初期値
        $returnVal = false;
        // FORM情報をセット
        $id = $this->input->post_get('id', true);
        // 削除処理
        $returnVal = $this->measurement_lib->Delete($id);
        // 関連受注情報削除

        return $returnVal;
    }


    /**
     * ソート変更処理
     *
     * @return void
     */
    public function SortAction() : void
    {
        // 読み込み時間を延長
        ini_set('max_execution_time', '90');
        // FORM情報
        $id = $this->input->post_get('id', true);
        $sortId = $this->input->post_get('sort_id', true);
        // ソート順が降順
        if (strtoupper(self::SORT_ARROW) == 'DESC') {
            // 並び順最大
            $sortMax = $this->db_lib->GetValueMax(measurement_lib::MASTER_TABLE);
            $sortId = ($sortMax - $sortId) + 1;
        }
        // ソート処理実行
        $this->measurement_lib->UpdateSort($id, $sortId);
    }


    /**
     * 場所IDが存在しない場合、ソート変更処理
     *
     * @param string $placeId : 場所ID
     * @return void
     */
    public function CheckPlaceId($placeId = "") : void
    {
        // 場所IDが存在しない場合、遷移させる
        if (!$this->place_lib->IdExists($placeId, true)) {
            redirect('/');
        }
    }


    /**
     * 計量記録一覧を取得
     *
     * @param array $whereSql : WHERE情報(配列形式)
     * @param array $orderSql : ORDER情報(配列→連想形式[key：対象カラム, arrow：矢印])
     * @param array $limitSql : LIMIT情報(連想配列形式[begin：開始行, row：件数])
     * @return array
     */
    public function GetList(
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
     * 入力フォーム用配列を取得
     *
     * @return array
     */
    public function FormInputList()
    {
        $returnVal = array(
            'code',
            'lot',
            'member_num',
            'packing_num',
            'confirm_flg',
            'cleaning_flg',
            'start_date',
        );
        return $returnVal;
    }


    /**
     * 入力フォーム用配列を取得
     *
     * @return array
     */
    public function FormSearchList()
    {
        $returnVal = array(
            'start_y',
            'start_m',
            'start_d',
            'end_y',
            'end_m',
            'end_d',
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
        // 品番
        $returnVal[] = array(
            'field'   => 'code',
            'label'   => '品番',
            'rules'   => 'required'
        );
        // ロット
        $returnVal[] = array(
            'field'   => 'lot',
            'label'   => 'ロット',
            'rules'   => 'required'
        );
        // 員数
        $returnVal[] = array(
            'field'   => 'member_num',
            'label'   => '員数',
            'rules'   => 'required|greater_than_equal_to[0]'
        );
        // 荷姿数量
        $returnVal[] = array(
            'field'   => 'packing_num',
            'label'   => '荷姿数量',
            'rules'   => 'required|greater_than_equal_to[0]'
        );
        // 状態確認N=3
        $returnVal[] = array(
            'field'   => 'confirm_flg',
            'label'   => '状態確認N=3',
            'rules'   => 'in_list[' . $this->base_lib->GetConvValidInList($this->measurement_lib->GetConfirmFlgList()) . ']'
        );
        // 秤周り清掃確認
        $returnVal[] = array(
            'field'   => 'cleaning_flg',
            'label'   => '秤周り清掃確認',
            'rules'   => 'in_list[' . $this->base_lib->GetConvValidInList($this->measurement_lib->GetCleaningFlgList()) . ']'
        );
        return $returnVal;
    }
}
