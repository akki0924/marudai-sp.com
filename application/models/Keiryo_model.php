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
        $returnVal['const'] = $this->work_lib->GetConstList('work_lib');

        $this->base_lib->ConsoleLogInst($returnVal);
        $this->base_lib->ConsoleLogInst(validation_errors());
        $this->base_lib->ConsoleLogInst($_SESSION);
        $this->base_lib->ConsoleLogInst($_POST);
        $this->base_lib->ConsoleLogInst($_FILES);

        return $returnVal;
    }


    /**
     * 入力テンプレート情報を取得
     *
     * @param string $placeCode：秤バーコード
     * @return array
     */
    public function InputTemplate(string $placeCode = '') : ?array
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
        $returnVal['form']['start_date'] = ($returnVal['form']['start_date'] ? $returnVal['form']['start_date'] : Base_lib::NowDateTime());
        foreach ($this->FormSearchList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        // 秤バーコードから秤IDを取得
        $placeId = $this->place_lib->GetIdFromCode($placeCode, true);
        // 秤データ詳細情報
        $returnVal['placeData'] = $this->place_lib->GetDetailValues($placeId, true);
        // 記録対象を指定
        $whereSql[] = Work_lib::MASTER_TABLE . " . place_type = " . $returnVal['placeData']['type'];
        // 期間を再セット
        $returnVal['form']['start_y'] = ($returnVal['form']['start_y'] ? $returnVal['form']['start_y'] : date('Y', strtotime("-1 week")));
        $returnVal['form']['start_m'] = ($returnVal['form']['start_m'] ? $returnVal['form']['start_m'] : date('m', strtotime("-1 week")));
        $returnVal['form']['start_d'] = ($returnVal['form']['start_d'] ? $returnVal['form']['start_d'] : date('d', strtotime("-1 week")));
        $returnVal['form']['end_y'] = ($returnVal['form']['end_y'] ? $returnVal['form']['end_y'] : date('Y'));
        $returnVal['form']['end_m'] = ($returnVal['form']['end_m'] ? $returnVal['form']['end_m'] : date('m'));
        $returnVal['form']['end_d'] = ($returnVal['form']['end_d'] ? $returnVal['form']['end_d'] : date('d'));
        // 期間を指定
        $startDate = $returnVal['form']['start_y'] . "-";
        $startDate .= $returnVal['form']['start_m'] . "-";
        $startDate .= $returnVal['form']['start_d'];
        $endDate =  $returnVal['form']['end_y'] . "-";
        $endDate .= $returnVal['form']['end_m'] . "-";
        $endDate .= $returnVal['form']['end_d'];
        $endDate = date("Y-m-d", strtotime($endDate . "+1 day"));
        $whereSql[] = Work_lib::MASTER_TABLE . " . start_date >= '" . $startDate . "'";
        $whereSql[] = Work_lib::MASTER_TABLE . " . end_date < '" . $endDate . "'";
        // ステータス
        $whereSql[] = Work_lib::MASTER_TABLE . " . status = " . Work_lib::ID_STATUS_ENABLE;
        // ORDER情報をセット
        $orderSql[0]['key'] = Work_lib::MASTER_TABLE . ' . regist_date';
        $orderSql[0]['arrow'] = 'ASC';
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList($whereSql, $orderSql, null, true);
        // 選択情報をセット
        $returnVal['select']['continue_flg'] = $this->work_lib->GetContinueFlgList();
        $returnVal['select']['confirm_flg'] = $this->work_lib->GetConfirmFlgList();
        $returnVal['select']['cleaning_flg'] = $this->work_lib->GetCleaningFlgList();
        $returnVal['select']['bousei_cleaning_flg'] = $this->work_lib->GetBouseiCleaningFlgList();
        $returnVal['select']['trash_flg'] = $this->work_lib->GetTrashFlgList();
        $returnVal['select']['year'] = $this->date_lib->GetYearList('年', '2021');
        $returnVal['select']['month'] = $this->date_lib->GetMonthList('月');
        $returnVal['select']['day'] = $this->date_lib->GetDayList('日');
        $returnVal['select']['worker'] =  $this->base_lib->SelectboxDefalutForm($this->worker_lib->GetNameList(true));

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
     * @param string $placeCode：秤バーコード
     * @return string
     */
    public function RegistAction($placeCode) : ?string
    {
        // 返値を初期化
        $returnVal = false;
        // 読み込み時間を延長
        ini_set('max_execution_time', '90');
        // FORM情報
        $id = $this->input->post_get('id', true);
        $action = $this->input->post_get('action', true);
        // 登録処理
        if (
            $action == 'add' &&
            $placeCode
        ) {
            // FORM情報をセット
            foreach ($this->FormInputList() as $key) {
                // 作業者 1人目
                if ($key == 'worker1') {
                    // 作業者データを取得
                    $workerData = $this->worker_lib->GetDetailValues($this->input->post_get($key, true));
                    if (isset($workerData['name_l'])) {
                        $form['worker1_id'] = $workerData['id'];
                        $form['worker1_name_l'] = $workerData['name_l'];
                        $form['worker1_name_f'] = $workerData['name_f'];
                    }
                }
                // 作業者 2人目
                elseif ($key == 'worker2') {
                    // 作業者データを取得
                    $workerData = $this->worker_lib->GetDetailValues($this->input->post_get($key, true));
                    if (isset($workerData['name_l'])) {
                        $form['worker2_id'] = $workerData['id'];
                        ;
                        $form['worker2_name_l'] = $workerData['name_l'];
                        $form['worker2_name_f'] = $workerData['name_f'];
                    }
                }
                // その他
                else {
                    // 登録情報にセット
                    $form[$key] = $this->input->post_get($key, true);
                    // カンマ情報を削除
                    if (
                        $key == 'member_num' ||
                        $key == 'packing_num' ||
                        $key == 'f_num' ||
                        $key == 'packing_num_total' ||
                        $key == 'bousei_num'
                    ) {
                        $form[$key] = str_replace(',', '', $form[$key]);
                    }
                }
            }
            // 秤バーコードから秤IDを取得
            $placeId = $this->place_lib->GetIdFromCode($placeCode, true);
            // 秤情報を取得
            $placeData = $this->place_lib->GetDetailValues($placeId, true);
            // 登録に必要な情報を追加
            $form['place_id'] = $placeId;
            $form['place_code'] = $placeData['code'];
            $form['place_ledger'] = $placeData['ledger'];
            $form['place_place'] = $placeData['place'];
            $form['place_type'] = $placeData['type'];
            $form['place_scale'] = $placeData['scale'];
            $form['status'] = Worker_lib::ID_STATUS_ENABLE;
            $form['end_date'] = date('Y-m-d H:i:s');
            // 記録対象が軽量記録の場合
            if ($placeData['type'] == Place_lib::ID_TYPE_MEASUREMENT) {
                // 継続フラグの削除
                unset($form['continue_flg']);
            }
            // 登録処理（IDを返す）
            $returnVal = $this->work_lib->Regist($form);
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
        $returnVal = $this->work_lib->Delete($id);
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
            $sortMax = $this->db_lib->GetValueMax(Work_lib::MASTER_TABLE);
            $sortId = ($sortMax - $sortId) + 1;
        }
        // ソート処理実行
        $this->work_lib->UpdateSort($id, $sortId);
    }


    /**
     * 現品コードより、現品情報（Ajax）を取得
     *
     * @return array
     */
    public function GetAjaxCodeAction() : array
    {
        // 返値を初期化
        $returnVal = array();
        $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = false;
        $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#pdf_url'] = '';

        // FORM情報を取得
        $inputCode = $this->input->post_get('input_code', true);
        $inputType = $this->input->post_get('type', true);
        // バーコード情報が存在
        if (
            $inputCode != '' &&
            $this->product_lib->CodeExists($inputCode, true)
        ) {
            // リアクションフラグを再セット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = true;
            // 現品IDを取得
            $id = $this->product_lib->GetIdFromCode($inputCode, true);
            // 現品詳細情報を取得
            $data['product'] = $this->product_lib->GetDetailValues($id, true);
            // 対象情報をセット
            $data['type'] = $inputType;
            $data['type_m'] = ($inputType == Place_lib::ID_TYPE_MEASUREMENT ? true : false);
            $data['type_o'] = ($inputType == Place_lib::ID_TYPE_OUTSOURCING ? true : false);
            $data['type_b'] = ($inputType == Place_lib::ID_TYPE_BOUSEI ? true : false);
            //$returnVal[Jscss_lib::KEY_AJAX_REACTION]['#input_group1'] = $this->load->view('keiryo_part_code', $data, true);
            //$returnVal[Jscss_lib::KEY_AJAX_REACTION]['#input_group2'] = $this->load->view('keiryo_part_num', $data, true);
            // 共通データの値をセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#number'] = $data['product']['number'];
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#lot'] = $data['product']['lot'];
            // 共通データのリアクションをセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#number'] = 'val';
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#lot'] = 'val';

            // 計量記録の場合
            if ($data['type_m']) {
                // 値をセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#member_num'] = $data['product']['member_num'];
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#packing_num'] = $data['product']['packing_num'];
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['.total_number'] = $data['product']['member_num'] * $data['product']['packing_num'];
                // リアクションをセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#member_num'] = 'val';
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#packing_num'] = 'val';
            }
            // 外注依頼記録の場合
            elseif ($data['type_o']) {
                // 値をセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#f_num'] = $data['product']['f_num'];
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#packing_num_total'] = $data['product']['packing_num_total'];
                // リアクションをセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#f_num'] = 'val';
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#packing_num_total'] = 'val';
            }
            // 防錆記録の場合
            elseif ($data['type_b']) {
                // 値をセット
                //$returnVal[Jscss_lib::KEY_AJAX_REACTION]['#bousei_num'] = $data['product']['bousei_num'];
                // リアクションをセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#bousei_num'] = 'val';
            }
            // 開始時間を更新
            // 値をセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#start_date'] = Base_lib::NowDateTime();
            // リアクションをセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#start_date'] = 'val';
            /*
            if ($data['type_m']) {
                // 合計金額をセット
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['.total_number'] = $data['product']['member_num'] * $data['product']['packing_num'];
            }
            */
            // PDFファイルの存在
            $pdfFileName =  $data['product']['id'] . '.pdf';
            if ($this->upload_lib->FileExists('pdf' . DIRECTORY_SEPARATOR . $pdfFileName)) {
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#inputCode'] = $this->upload_lib->GetSrcWebPath('pdf' . DIRECTORY_SEPARATOR . $pdfFileName);
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#inputCode'] = 'val';
            }
        }
        return $returnVal;
    }


    /**
     * 品番よりより、PDF情報（Ajax）を取得
     *
     * @return array
     */
    public function GetAjaxPdfAction() : array
    {
        // 返値を初期化
        $returnVal = array();
        $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = false;
        $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#pdf_url'] = '';

        // FORM情報を取得
        $inputNumber = $this->input->post_get('number', true);
        // バーコード情報が存在
        if (
            $inputNumber != '' &&
            $this->product_lib->NumberExists($inputNumber, true)
        ) {
            // リアクションフラグを再セット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = true;
            // 現品IDを取得
            $id = $this->product_lib->GetIdFromNumber($inputNumber, true);
            // 現品詳細情報を取得
            $product = $this->product_lib->GetDetailValues($id, true);
            // PDFファイルの存在
            $pdfFileName =  $product['id'] . '.pdf';
            if ($this->upload_lib->FileExists('pdf' . DIRECTORY_SEPARATOR . $pdfFileName)) {
                $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#inputCode'] = $this->upload_lib->GetSrcWebPath('pdf' . '/' . $pdfFileName);
                $returnVal[Jscss_lib::KEY_AJAX_REACTION_FUNC]['#inputCode'] = 'val';
            }
        }
        return $returnVal;
    }


    /**
     * 一覧リスト更新（Ajax）
     *
     * @return array
     */
    public function GetAjaxListAction() : array
    {
        // 返値を初期化
        $returnVal = array();
        $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = false;

        // FORM情報を取得
        $startY = $this->input->post_get('start_y', true);
        $startM = $this->input->post_get('start_m', true);
        $startD = $this->input->post_get('start_d', true);
        $endY = $this->input->post_get('end_y', true);
        $endM = $this->input->post_get('end_m', true);
        $endD = $this->input->post_get('end_d', true);
        $inputType = $this->input->post_get('type', true);
        // 記録対象
        if ($inputType) {
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
                $endDate =  date('Y-m-d', mktime(0, 0, 0, $endM, $endD + 1, $endY));
                $whereSql[] = Work_lib::MASTER_TABLE . " . end_date < '" . $endDate . "'";
            }
            // ORDER情報をセット
            $orderSql[0]['key'] = Work_lib::MASTER_TABLE . ' . regist_date';
            $orderSql[0]['arrow'] = 'ASC';
            // 一覧情報を取得
            $data['list'] = $this->GetList($whereSql, $orderSql, null, true);
            // 記録対象をセット
            $data['type'] = $inputType;
            // テンプレート情報をセット
            $returnVal[Jscss_lib::KEY_AJAX_REACTION]['#search_list'] = $this->load->view('keiryo_part_list', $data, true);
        }
        return $returnVal;
    }


    /**
     * 秤バーコードが存在しない場合、TOPページへ遷移
     *
     * @param string $placeCode：秤バーコード
     * @return void
     */
    public function CheckPlaceCode(string $placeCode = '') : void
    {
        // 秤バーコードが存在しない場合、遷移させる
        if (!$this->place_lib->CodeExists($placeCode, true)) {
            redirect('/');
        }
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
    public function FormInputList()
    {
        $returnVal = array(
            'start_date',
            'number',
            'lot',
            'member_num',
            'f_num',
            'bousei_num',
            'packing_num',
            'packing_num_total',
            'continue_flg',
            'confirm_flg',
            'cleaning_flg',
            'comment',
            'worker1',
            'worker2',
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
     * @param string $placeCode：秤バーコード
     * @return array
     */
    public function ConfigInputValues(string $placeCode = '') : array
    {
        // 品番
        $returnVal[] = array(
            'field'   => 'number',
            'label'   => '品番',
            'rules'   => 'required'
        );
        // ロット
        //$returnVal[] = array(
        //    'field'   => 'lot',
        //    'label'   => 'ロット',
        //    'rules'   => 'required'
        //);
        // 秤バーコードから秤IDを取得
        $placeId = $this->place_lib->GetIdFromCode($placeCode, true);
        // 秤データ詳細情報を取得
        $placeData = $this->place_lib->GetDetailValues($placeId, true);
        // 記録対象が計量記録
        if ($placeData['type'] == Place_lib::ID_TYPE_MEASUREMENT) {
            // 員数
            //$returnVal[] = array(
            //    'field'   => 'member_num',
            //    'label'   => '員数',
            //    'rules'   => 'required|greater_than_equal_to[0]'
            //);
            $returnVal[] = array(
                'field'   => 'member_num',
                'label'   => '員数',
                'rules'   => 'required'
            );
            // 荷姿数量
            //$returnVal[] = array(
            //    'field'   => 'packing_num',
            //    'label'   => '荷姿数量',
            //    'rules'   => 'required|greater_than_equal_to[0]'
            //);
            $returnVal[] = array(
                'field'   => 'packing_num',
                'label'   => '荷姿数量',
                'rules'   => 'required'
            );
        }
        // 記録対象が外注依頼記録
        elseif ($placeData['type'] == Place_lib::ID_TYPE_OUTSOURCING) {
            // 現場エフ数量
            //$returnVal[] = array(
            //    'field'   => 'f_num',
            //    'label'   => '現場エフ数量',
            //    'rules'   => 'required|greater_than_equal_to[0]'
            //);
            $returnVal[] = array(
                'field'   => 'f_num',
                'label'   => '現場エフ数量',
                'rules'   => 'required'
            );
            // 実荷姿数量
            //$returnVal[] = array(
            //    'field'   => 'packing_num_total',
            //    'label'   => '実荷姿数量',
            //    'rules'   => 'required|greater_than_equal_to[0]'
            //);
            $returnVal[] = array(
                'field'   => 'packing_num_total',
                'label'   => '実荷姿数量',
                'rules'   => 'required'
            );
        }
        // 記録対象が防錆記録
        elseif ($placeData['type'] == Place_lib::ID_TYPE_BOUSEI) {
            // 数量
            //$returnVal[] = array(
            //    'field'   => 'bousei_num',
            //    'label'   => '数量',
            //    'rules'   => 'required|greater_than_equal_to[0]'
            //);
            $returnVal[] = array(
                'field'   => 'bousei_num',
                'label'   => '数量',
                'rules'   => 'required'
            );
        }
        // 作業者一覧をセット
        $workerList = $this->base_lib->GetConvValidInList($this->worker_lib->GetNameList());
        // 作業者１
        $returnVal[] = array(
            'field'   => 'worker1',
            'label'   => '作業者１',
            'rules'   => 'required|in_list[' . $workerList . ']'
        );
        // 作業者２
        $returnVal[] = array(
            'field'   => 'worker2',
            'label'   => '作業者２',
            'rules'   => 'in_list[' . $workerList . ']'
        );
        // 記録対象が計量記録
        if ($placeData['type'] == Place_lib::ID_TYPE_OUTSOURCING) {
            // 継続フラグ
            $returnVal[] = array(
                'field'   => 'continue_flg',
                'label'   => '継続フラグ',
                'rules'   => 'required|in_list[' . $this->base_lib->GetConvValidInList($this->work_lib->GetContinueFlgList()) . ']'
            );
        }
        // 記録対象が防錆記録
        elseif ($placeData['type'] == Place_lib::ID_TYPE_BOUSEI) {
            // 継続フラグ
            $returnVal[] = array(
                'field'   => 'continue_flg',
                'label'   => '継続フラグ',
                'rules'   => 'required|in_list[' . $this->base_lib->GetConvValidInList($this->work_lib->GetContinueFlgList()) . ']'
            );
        }
        // 状態確認N=3
        $returnVal[] = array(
            'field'   => 'confirm_flg',
            'label'   => '状態確認N=3',
            'rules'   => 'in_list[' . $this->base_lib->GetConvValidInList($this->work_lib->GetConfirmFlgList()) . ']'
        );
        // 秤周り清掃確認
        $returnVal[] = array(
            'field'   => 'cleaning_flg',
            'label'   => '秤周り清掃確認',
            'rules'   => 'in_list[' . $this->base_lib->GetConvValidInList($this->work_lib->GetCleaningFlgList()) . ']'
        );
        return $returnVal;
    }
}
