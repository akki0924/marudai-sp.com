<?php
/**
 * 作成者追加画面用モデル
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 0.0.1
 * @since 0.0.1     2021/12/22：新規作成
 */
class Worker_model extends MY_Model
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
        $this->load->library(Base_lib::MASTER_DIR . '/worker_lib');
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
        // ORDER情報をセット
        $orderSql[0]['key'] = Worker_lib::MASTER_TABLE . ' . regist_date';
        $orderSql[0]['arrow'] = 'ASC';
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList(null, $orderSql, null, true);

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
            foreach ($this->FormWorkerList() as $key) {
                // 登録情報にセット
                $form[$key] = $this->input->post_get($key, true);
            }
            // 登録に必要な情報を追加
            $form['status'] = Worker_lib::ID_STATUS_ENABLE;
            // 登録処理（IDを返す）
            $returnVal = $this->worker_lib->Regist($form);
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
        $returnVal = $this->worker_lib->Delete($id);
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
            $sortMax = $this->db_lib->GetValueMax(worker_lib::MASTER_TABLE);
            $sortId = ($sortMax - $sortId) + 1;
        }
        // ソート処理実行
        $this->worker_lib->UpdateSort($id, $sortId);
    }


    /**
     * 一覧を取得
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
            $orderSql[0]['key'] = Worker_lib::MASTER_TABLE . ' . regist_date';
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
                " . Worker_lib::MASTER_TABLE . " . id,
                CONCAT(" . Worker_lib::MASTER_TABLE . " . name_l, ' ', " . Worker_lib::MASTER_TABLE . " . name_f) AS name,
                " . Worker_lib::MASTER_TABLE . " . name_l,
                " . Worker_lib::MASTER_TABLE . " . name_f,
                " . Worker_lib::MASTER_TABLE . " . status,
                CASE " . Worker_lib::MASTER_TABLE . " . status
                    WHEN " . Worker_lib::ID_STATUS_ENABLE . " THEN '" . Worker_lib::NAME_STATUS_ENABLE . "'
                    ELSE '" . Worker_lib::NAME_STATUS_DISABLE . "'
                END status_name,
                " . Worker_lib::MASTER_TABLE . " . regist_date,
                DATE_FORMAT(" . Worker_lib::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . Worker_lib::MASTER_TABLE . " . edit_date,
                DATE_FORMAT(" . Worker_lib::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . Worker_lib::MASTER_TABLE . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            " . $orderSqlVal . "
            " . (isset($limitSqlVal) ? $limitSqlVal : '') . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
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
