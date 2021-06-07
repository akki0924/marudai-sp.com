\<\?php
/**
 * <?= $comment ?>画面用モデル
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     <?= date('Y/m/d') ?>：新規作成
 */
class <?= ucfirst($targetName) ?>_model extends CI_Model
{
    const DEFAULT_LIST_COUNT = 200;
    const FIRST_MSG = '検索項目を選択してください。';
    const NO_LIST_MSG = '一覧リストが見つかりません。';
    // 並び順用文字列
    const SORT_COLUMN = 'sort_id';
    const SORT_ARROW = 'desc';
    // ログイン対象
    const LOGIN_KEY = Base_lib::ADMIN_DIR;


    /**
     * コントラクト
     */
    public function __construct()
    {
        $loginTarget['key'] = self::LOGIN_KEY;
        // ライブラリー読込み
        $this->load->library('login_lib', $loginTarget);
        // ログイン情報の確認
        if (! $this->login_lib->LoginCheck()) {
            // エラーページへ遷移
            redirect(Base_lib::ADMIN_DIR ."/index/error");
        }
        // ライブラリー読込み
        $this->load->library(Base_lib::MASTER_DIR . '/<?= $targetName ?>_lib');
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
        $returnVal['const'] = $this->base_lib->GetBaseConstList();
        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog(validation_errors());
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog($_POST);
        Base_lib::ConsoleLog($_FILES);

        return $returnVal;
    }


    /**
     * 一覧テンプレート情報を取得
     *
     * @return array
     */
    public function ListTemplate() : ?array
    {
        // 読み込み時間を延長
        ini_set('max_execution_time', '90');
        // 各ライブラリの読み込み
        $this->load->library('pagenavi_lib');
        // 返値を初期化
        $returnVal = array();
        // WHERE情報をセット
        $whereSql = array();
        // FORM情報をセット
        $returnVal['action'] = $this->input->post_get('action', true);
        foreach ($this->FormDefaultList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
        // WHERE情報をセット
        $whereSql = array();
        // 一覧数の取得
        $returnVal['count'] = $this->GetListCount($whereSql);
        // ORDER情報をセット
        $orderSql[0]['key'] = <?= $targetName ?>_lib::MASTER_TABLE . ' . sort_id';
        $orderSql[0]['arrow'] = 'DESC';
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList($whereSql, $orderSql, null, true);
        // FROM値の有無によって表示内容を変更してセット
        $returnVal['no_list_msg'] = self::NO_LIST_MSG;

        return $this->sharedTemplate($returnVal);
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
        // <?= $comment ?>情報が存在有無情報をセット
        $exists =  $this-><?= $targetName ?>_lib->IdExists($id);
        $returnVal['exists'] = $exists;
        // 選択情報をセット
        $returnVal['select']['status'] = $this-><?= $targetName ?>_lib->GetStatusList();
        if ($action == '') {
            // <?= $comment ?>ID存在しない
            if (!$exists) {
                // FORM情報をセット
                foreach ($this->FormInputList() as $key) {
                    $returnVal['form'][$key] = $this->input->post_get($key, true);
                }
            }
            // <?= $comment ?>IDが存在
            else {
                // <?= $comment ?>詳細情報を取得
                $returnVal['form'] = $this-><?= $targetName ?>_lib->GetDetailValues($id);
            }
        }
        // 遷移アクション時
        else {
            // FORM情報をセット
            foreach ($this->FormInputList() as $key) {
                $returnVal['form'][$key] = $this->input->post_get($key, true);
            }
            // バリデーションOK時
            if ($validFlg) {
                // 各選択情報の表示名をセット
                $returnVal['form']['status_name'] = $this-><?= $targetName ?>_lib->GetStatusName($returnVal['form']['status']);
            }
        }

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 入力完了テンプレート情報を取得
     *
     * @param ?string $id：対象ID
     * @return array
     */
    public function CompTemplate($id = '')
    {
        // 返値を初期化
        $returnVal = array();
        // <?= $comment ?>情報が存在有無情報をセット
        $exists =  $this-><?= $targetName ?>_lib->IdExists($id);
        $returnVal['exists'] = $exists;

        return $this->sharedTemplate($returnVal);
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
        // 完了画面
        if (
            $action == 'comp' &&
            $validFlg == true
        ) {
            // FORM情報をセット
            foreach ($this->FormInputList() as $key) {
                // 登録情報にセット
                $form[$key] = $this->input->post_get($key, true);
            }
            // 登録処理（IDを返す）
            $returnVal = $this-><?= $targetName ?>_lib->Regist($form, $id);
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
        $returnVal = $this-><?= $targetName ?>_lib->Delete($id);
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
            $sortMax = $this->db_lib->GetValueMax(<?= $targetName ?>_lib::MASTER_TABLE);
            $sortId = ($sortMax - $sortId) + 1;
        }
        // ソート処理実行
        $this-><?= $targetName ?>_lib->UpdateSort($id, $sortId);
    }


    /**
     * 一覧数を取得
     *
     * @param array $whereSql : WHERE情報(配列形式)
     * @return string
     */
    public function GetListCount(?array $whereSql = array()) : ?string
    {
        return $this->db_lib->GetCount(<?= $targetName ?>_lib::MASTER_TABLE, $whereSql);
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
    ) : ? array
    {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報を再セット
        if (! is_array($whereSql)) {
            $whereSql = [];
        }
        // ORDER情報を再セット
        if (! is_array($orderSql)) {
            $orderSql = array();
            $orderSql[0]['key'] = <?= $targetName ?>_lib::MASTER_TABLE . ' . regist_date';
            $orderSql[0]['arrow'] = 'DESC';
        }
        // ORDER文を生成
        $orderSqlVal = 'ORDER BY';
        for ($i = 0, $n = count($orderSql); $i < $n; $i ++) {
            $orderSqlVal .= ' ' . $orderSql[$i]['key'] . ' ' . $orderSql[$i]['arrow'];
        }
        // LIMIT文を生成
        if (is_array($limitSql)) {
            $limitSqlVal = 'LIMIT ' . $limitSql['begin'] . ', ' . $limitSql['row'];
        }
        $query = $this->db->query("
            SELECT
<?php for ($i = 0, $n = count($table); $i < $n; $i ++) { ?>
                " . <?= $targetName ?>_lib::MASTER_TABLE . " . <?= $table[$i]['name'] ?>,
<?php if ($table[$i]['name'] == 'status') { ?>
                CASE " . <?= $targetName ?>_lib::MASTER_TABLE . " . status
                    WHEN " . <?= $targetName ?>_lib::ID_STATUS_ENABLE . " THEN '" . <?= $targetName ?>_lib::NAME_STATUS_ENABLE . "'
                    ELSE '" . <?= $targetName ?>_lib::NAME_STATUS_DISABLE . "'
                END status_name,
<?php } ?>
<?php if ($table[$i]['name'] == 'regist_date') { ?>
                DATE_FORMAT(" . <?= $targetName ?>_lib::MASTER_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
<?php } ?>
<?php if ($table[$i]['name'] == 'edit_date') { ?>
                DATE_FORMAT(" . <?= $targetName ?>_lib::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
<?php } ?>
<?php } ?>
            FROM " . <?= $targetName ?>_lib::MASTER_TABLE . "
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
     * 一覧フォーム用配列を取得
     *
     * @return array
     */
    public function FormDefaultList() : array
    {
        $returnVal = array(
            'select_status',
        );

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
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
            '<?= $tableSel[$i]['name'] ?>',
<?php } ?>
        );

        return $returnVal;
    }


    /**
     * 入力ページ エラーチェック配列
     *
     * @return array
     */
    public function ConfigInputValues() : array
    {
<?php for ($i = 0, $n = count($tableSel); $i < $n; $i ++) { ?>
        // <?= $tableSel[$i]['comment'] ?>

        $returnVal[] = array(
            'field'   => '<?= $tableSel[$i]['name'] ?>',
            'label'   => '<?= $tableSel[$i]['comment'] ?>',
            'rules'   => 'required'
        );
<?php } ?>

        return $returnVal;
    }
}
