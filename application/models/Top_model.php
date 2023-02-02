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
        $this->load->library(Base_lib::MASTER_DIR . '/product_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/worker_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/place_lib');
        $this->load->library(Base_lib::MASTER_DIR . '/work_lib');
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
        $action = $this->input->post_get('action', true);
        // FORM情報をセット
        foreach ($this->FormWorkerList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        // 期間を再セット
        $returnVal['form']['start_y1'] = ($returnVal['form']['start_y1'] ? $returnVal['form']['start_y1'] : date('Y', strtotime("-1 week")));
        $returnVal['form']['start_m1'] = ($returnVal['form']['start_m1'] ? $returnVal['form']['start_m1'] : date('m', strtotime("-1 week")));
        $returnVal['form']['start_d1'] = ($returnVal['form']['start_d1'] ? $returnVal['form']['start_d1'] : date('d', strtotime("-1 week")));
        $returnVal['form']['end_y1'] = ($returnVal['form']['end_y1'] ? $returnVal['form']['end_y1'] : date('Y'));
        $returnVal['form']['end_m1'] = ($returnVal['form']['end_m1'] ? $returnVal['form']['end_m1'] : date('m'));
        $returnVal['form']['end_d1'] = ($returnVal['form']['end_d1'] ? $returnVal['form']['end_d1'] : date('d'));
        // 期間を再セット
        $returnVal['form']['start_y2'] = ($returnVal['form']['start_y2'] ? $returnVal['form']['start_y2'] : date('Y', strtotime("-1 week")));
        $returnVal['form']['start_m2'] = ($returnVal['form']['start_m2'] ? $returnVal['form']['start_m2'] : date('m', strtotime("-1 week")));
        $returnVal['form']['start_d2'] = ($returnVal['form']['start_d2'] ? $returnVal['form']['start_d2'] : date('d', strtotime("-1 week")));
        $returnVal['form']['end_y2'] = ($returnVal['form']['end_y2'] ? $returnVal['form']['end_y2'] : date('Y'));
        $returnVal['form']['end_m2'] = ($returnVal['form']['end_m2'] ? $returnVal['form']['end_m2'] : date('m'));
        $returnVal['form']['end_d2'] = ($returnVal['form']['end_d2'] ? $returnVal['form']['end_d2'] : date('d'));
        // 期間を再セット
        $returnVal['form']['start_y3'] = ($returnVal['form']['start_y3'] ? $returnVal['form']['start_y3'] : date('Y', strtotime("-1 week")));
        $returnVal['form']['start_m3'] = ($returnVal['form']['start_m3'] ? $returnVal['form']['start_m3'] : date('m', strtotime("-1 week")));
        $returnVal['form']['start_d3'] = ($returnVal['form']['start_d3'] ? $returnVal['form']['start_d3'] : date('d', strtotime("-1 week")));
        $returnVal['form']['end_y3'] = ($returnVal['form']['end_y3'] ? $returnVal['form']['end_y3'] : date('Y'));
        $returnVal['form']['end_m3'] = ($returnVal['form']['end_m3'] ? $returnVal['form']['end_m3'] : date('m'));
        $returnVal['form']['end_d3'] = ($returnVal['form']['end_d3'] ? $returnVal['form']['end_d3'] : date('d'));
        // WHERE文を初期化
        $whereSqlM = array();
        $whereSqlO = array();
        // 計量記録検索
        $startDate =  $returnVal['form']['start_y1'] . "-";
        $startDate .= $returnVal['form']['start_m1'] . "-";
        $startDate .= $returnVal['form']['start_d1'];
        $endDate =  $returnVal['form']['end_y1'] . "-";
        $endDate .= $returnVal['form']['end_m1'] . "-";
        $endDate .= $returnVal['form']['end_d1'];
        $endDate = date("Y-m-d", strtotime($endDate . "+1 day"));
        $whereSqlM[] = Work_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
        $whereSqlM[] = Work_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        $whereSqlM[] = Work_lib::MASTER_TABLE . " . place_type = " . Place_lib::ID_TYPE_MEASUREMENT;
        $whereSqlM[] = Work_lib::MASTER_TABLE . " . status = " . Work_lib::ID_STATUS_ENABLE;
        // 外注依頼記録検索
        $startDate =  $returnVal['form']['start_y2'] . "-";
        $startDate .= $returnVal['form']['start_m2'] . "-";
        $startDate .= $returnVal['form']['start_d2'];
        $endDate =  $returnVal['form']['end_y2'] . "-";
        $endDate .= $returnVal['form']['end_m2'] . "-";
        $endDate .= $returnVal['form']['end_d2'];
        $endDate = date("Y-m-d", strtotime($endDate . "+1 day"));
        $whereSqlO[] = Work_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
        $whereSqlO[] = Work_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        $whereSqlO[] = Work_lib::MASTER_TABLE . " . place_type = " . Place_lib::ID_TYPE_OUTSOURCING;
        $whereSqlO[] = Work_lib::MASTER_TABLE . " . status = " . Work_lib::ID_STATUS_ENABLE;
        // 防錆記録検索
        $startDate =  $returnVal['form']['start_y3'] . "-";
        $startDate .= $returnVal['form']['start_m3'] . "-";
        $startDate .= $returnVal['form']['start_d3'];
        $endDate =  $returnVal['form']['end_y3'] . "-";
        $endDate .= $returnVal['form']['end_m3'] . "-";
        $endDate .= $returnVal['form']['end_d3'];
        $endDate = date("Y-m-d", strtotime($endDate . "+1 day"));
        $whereSqlB[] = Work_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
        $whereSqlB[] = Work_lib::MASTER_TABLE . " . end_date <= '" . $endDate . "'";
        $whereSqlB[] = Work_lib::MASTER_TABLE . " . place_type = " . Place_lib::ID_TYPE_BOUSEI;
        $whereSqlB[] = Work_lib::MASTER_TABLE . " . status = " . Work_lib::ID_STATUS_ENABLE;
        // ORDER情報をセット
        $orderSql[0]['key'] = Work_lib::MASTER_TABLE . ' . regist_date';
        $orderSql[0]['arrow'] = 'ASC';
        // 一覧情報を取得
        $returnVal['list_m'] = $this->GetList($whereSqlM, $orderSql, null);
        $returnVal['list_o'] = $this->GetList($whereSqlO, $orderSql, null);
        $returnVal['list_b'] = $this->GetList($whereSqlB, $orderSql, null);
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
     * 計量記録/外部依頼記録一覧リスト更新（Ajax）
     *
     * @return array
     */
    public function GetAjaxListAction() : array
    {
        // 返値を初期化
        $returnVal = array();
        $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = false;

        // FORM情報を取得
        $inputType = $this->input->post_get('type', true);
        // 記録対象
        if ($inputType) {
            $startY = $this->input->post_get('start_y', true);
            $startM = $this->input->post_get('start_m', true);
            $startD = $this->input->post_get('start_d', true);
            $endY = $this->input->post_get('end_y', true);
            $endM = $this->input->post_get('end_m', true);
            $endD = $this->input->post_get('end_d', true);

            // リアクションフラグを再セット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = true;
            // WHERE情報をセット
            $whereSql[] = Work_lib::MASTER_TABLE . " . place_type = " . $inputType;
            $whereSql[] = Work_lib::MASTER_TABLE . " . status = " . Work_lib::ID_STATUS_ENABLE;
            // 開始日
            if (
                $startY != '' &&
                $startM != '' &&
                $startD != ''
            ) {
                $startDate =  $startY . "-" . $startM . "-" . $startD;
                $whereSql[] = Work_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
            }
            // 終了日
            if (
                $endY != '' &&
                $endM != '' &&
                $endD != ''
            ) {
                $endDate =  $endY . "-" . $endM . "-" . $endD;
                $endDate = date("Y-m-d", strtotime($endDate . "+1 day"));
                $whereSql[] = Work_lib::MASTER_TABLE . " . end_date < '" . $endDate . "'";
            }
            // ORDER情報をセット
            $orderSql[0]['key'] = Work_lib::MASTER_TABLE . ' . regist_date';
            $orderSql[0]['arrow'] = 'ASC';
            // 一覧情報を取得
            $data['list'] = $this->GetList($whereSql, $orderSql, null);
            // 記録対象をセット
            $data['type'] = $inputType;
            if ($inputType == Place_lib::ID_TYPE_MEASUREMENT) {
                $templateType = 'm';
            } elseif ($inputType == Place_lib::ID_TYPE_OUTSOURCING) {
                $templateType = 'o';
            } elseif ($inputType == Place_lib::ID_TYPE_BOUSEI) {
                $templateType = 'b';
            } else {
                $templateType = '';
            }
            // テンプレート情報をセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#search_' . $templateType . '_list'] = $this->load->view('index_part_list', $data, true);
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#search_' . $templateType . '_print'] = $this->load->view('index_part_list', $data, true);
        }
        return $returnVal;
    }


    /**
     * 計量記録/外部依頼記録一覧を取得
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
            $orderSql[0]['key'] = Work_lib::MASTER_TABLE . ' . regist_date';
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
                " . Work_lib::MASTER_TABLE . " . id,
                DATE_FORMAT(" . Work_lib::MASTER_TABLE . " . start_date, '%c/%e') AS start_date,
                DATE_FORMAT(" . Work_lib::MASTER_TABLE . " . start_date, '%H:%i') AS start_time,
                " . Work_lib::MASTER_TABLE . " . worker1_name_l,
                " . Work_lib::MASTER_TABLE . " . worker1_name_f,
                CONCAT(" . Work_lib::MASTER_TABLE . " . worker1_name_l, " . Work_lib::MASTER_TABLE . " . worker1_name_f) AS worker1_name,
                " . Work_lib::MASTER_TABLE . " . worker2_name_l,
                " . Work_lib::MASTER_TABLE . " . worker2_name_f,
                CONCAT(" . Work_lib::MASTER_TABLE . " . worker2_name_l, " . Work_lib::MASTER_TABLE . " . worker2_name_f) AS worker2_name,
                " . Work_lib::MASTER_TABLE . " . place_code,
                " . Work_lib::MASTER_TABLE . " . place_scale,
                " . Work_lib::MASTER_TABLE . " . place_ledger,
                " . Work_lib::MASTER_TABLE . " . place_place,
                CASE " . Work_lib::MASTER_TABLE . " . place_place
                    WHEN " . Place_lib::ID_PLACE_MAIN . " THEN '" . Place_lib::NAME_PLACE_MAIN . "'
                    WHEN " . Place_lib::ID_PLACE_YORO . " THEN '" . Place_lib::NAME_PLACE_YORO . "'
                    ELSE ''
                END place_name,
                " . Work_lib::MASTER_TABLE . " . place_type,
                " . Work_lib::MASTER_TABLE . " . place_scale,
                " . Product_lib::MASTER_TABLE . " . id AS product_id,
                " . Work_lib::MASTER_TABLE . " . number,
                " . Work_lib::MASTER_TABLE . " . lot,
                CASE " . Work_lib::MASTER_TABLE . " . place_type
                    WHEN " . Place_lib::ID_TYPE_MEASUREMENT . " THEN " . Work_lib::MASTER_TABLE . " . member_num
                    WHEN " . Place_lib::ID_TYPE_OUTSOURCING . " THEN " . Work_lib::MASTER_TABLE . " . f_num
                    WHEN " . Place_lib::ID_TYPE_BOUSEI . " THEN " . Work_lib::MASTER_TABLE . " . bousei_num
                    ELSE ''
                END num,
                CASE " . Work_lib::MASTER_TABLE . " . place_type
                    WHEN " . Place_lib::ID_TYPE_MEASUREMENT . " THEN " . Work_lib::MASTER_TABLE . " . packing_num
                    WHEN " . Place_lib::ID_TYPE_OUTSOURCING . " THEN " . Work_lib::MASTER_TABLE . " . packing_num_total
                    ELSE ''
                END packing,
                CASE " . Work_lib::MASTER_TABLE . " . place_type
                    WHEN " . Place_lib::ID_TYPE_MEASUREMENT . " THEN (" . Work_lib::MASTER_TABLE . " . member_num * " . Work_lib::MASTER_TABLE . " . packing_num)
                    ELSE ''
                END total_num,
                " . Work_lib::MASTER_TABLE . " . continue_flg,
                CASE " . Work_lib::MASTER_TABLE . " . continue_flg
                    WHEN " . Work_lib::ID_CONTINUE_FLG_REPEAT . " THEN '" . Work_lib::NAME_CONTINUE_FLG_REPEAT . "'
                    WHEN " . Work_lib::ID_CONTINUE_FLG_END . " THEN '" . Work_lib::NAME_CONTINUE_FLG_END . "'
                    ELSE ''
                END continue_flg_name,
                " . Work_lib::MASTER_TABLE . " . confirm_flg,
                " . Work_lib::MASTER_TABLE . " . cleaning_flg,
                " . Work_lib::MASTER_TABLE . " . status,
                " . Work_lib::MASTER_TABLE . " . regist_date,
                DATE_FORMAT(" . Work_lib::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . Work_lib::MASTER_TABLE . " . edit_date,
                DATE_FORMAT(" . Work_lib::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . Work_lib::MASTER_TABLE . "
            LEFT OUTER JOIN " . Product_lib::MASTER_TABLE . " ON " . Work_lib::MASTER_TABLE . " . number = " . Product_lib::MASTER_TABLE . " . number
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            " . $orderSqlVal . "
            " . (isset($limitSqlVal) ? $limitSqlVal : '') . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
            for ($i = 0, $n = count($returnVal); $i < $n; $i ++) {
                // PDF存在フラグデータを初期化してセット
                $returnVal[$i]['pdf_exists'] = false;
                // 対象データのPDFの存在確認
                if ($this->upload_lib->FileExists('pdf' . DIRECTORY_SEPARATOR . $returnVal[$i]['product_id'] . '.pdf')) {
                    // PDF存在フラグ情報を再セット
                    $returnVal[$i]['pdf_exists'] = true;
                }
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
            'start_y1',
            'start_m1',
            'start_d1',
            'end_y1',
            'end_m1',
            'end_d1',
            'start_y2',
            'start_m2',
            'start_d2',
            'end_y2',
            'end_m2',
            'end_d2',
            'start_y3',
            'start_m3',
            'start_d3',
            'end_y3',
            'end_m3',
            'end_d3',
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
