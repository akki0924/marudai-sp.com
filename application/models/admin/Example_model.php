<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 雛形データ用ライブラリー
 *
 * 雛形データの取得および処理する為の関数群 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/04/28：新規作成
 */
class Example_model extends CI_Model
{
    /**
     * const
     */
    // ログイン対象
    const LOGIN_KEY = Base_lib::ADMIN_DIR;


    /**
     * コントラクト
     */
    public function __construct()
    {
        // 一覧テンプレート情報を取得
        $this->load->library('login_lib', array('key' => self::LOGIN_KEY));
    }


    /**
     * 共通テンプレート
     *
     * @param array|null $returnVal：各テンプレート用配列
     * @return array
     */
    public function sharedTemplate(?array $returnVal = array()) : array
    {
        // クラス定数をセット
        $returnVal['const'] = $this->GetBaseConstList();
        // ログ出力
        Base_lib::ConsoleLog($returnVal);
        Base_lib::ConsoleLog($_SERVER);
        Base_lib::ConsoleLog($_SESSION);
        Base_lib::ConsoleLog(validation_errors());

        return $returnVal;
    }


    /**
     * 一覧テンプレート情報を取得
     *
     * @param string $id：ID
     * @param boolean $validFlg
     * @return array|null
     */
    public function ListTemplate(string $id = '') : ?array
    {
        // 読み込み時間を延長
        ini_set('max_execution_time', '90');

        // 返値を初期化
        $returnVal = array();

        // 各ライブラリの読み込み
        $this->load->library('pagenavi_lib');

        // WHERE情報をセット
        $whereSql = array();

        // 選択情報をセット
        $returnVal['select'][count] = $this->pagenavi_lib->GetListCount()
        // FORM情報をセット
        $returnVal['action'] = $this->input->post_get('action', true);
        foreach ($this->FormDefaultList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }

        // ページ一覧用の情報を取得
        $returnVal['form']['select_list_count'] = ($returnVal['form']['select_list_count'] != '' ? $returnVal['form']['select_list_count'] : Pagenavi_lib::DEFAULT_LIST_COUNT);

        // WHERE情報をセット
        $whereSql = array();
        // キーワード
        if ($returnVal['form']['search_keyword'] != '') {
            $whereSqlSearch[] = User_lib::MASTER_TABLE . " . id LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";
            $whereSqlSearch[] = User_lib::MASTER_TABLE . " . l_name LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";
            $whereSqlSearch[] = User_lib::MASTER_TABLE . " . f_name LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";
            $whereSqlSearch[] = User_lib::MASTER_TABLE . " . tel LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";
            $whereSql[] = "(" . @implode(" OR ", $whereSqlSearch) . ")";
            unset($whereSqlSearch);
        }

        // 一覧数の取得
        $returnVal['count'] = $this->GetListCount($whereSql);
        // ページナビ情報を取得
        $returnVal['pager'] = $this->pagenavi_lib->GetValeus($returnVal['count'], $returnVal['form']['page'], $returnVal['form']['select_list_count']);
        // ORDER情報をセット
        $orderSql[0]['key'] = User_lib::MASTER_TABLE . ' . edit_date';
        $orderSql[0]['arrow'] = 'DESC';
        // LIMIT情報をセット
        $limitSql['begin'] = ($returnVal['pager']['listStart'] - 1);
        $limitSql['row'] = $returnVal['form']['select_list_count'];
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList($whereSql, $orderSql, $limitSql);

        // FROM値の有無によって表示内容を変更してセット
        $returnVal['no_list_msg'] = self::NO_LIST_MSG;

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 一覧フォーム用配列
     *
     * @return array
     */
    public function FormDefaultList() : array
    {
        $returnVal = array(
            'page',
            'select_count',
            'search_keyword',
        );
        return $returnVal;
    }


    /**
     * 入力フォーム用配列
     *
     * @return array
     */
    public function FormInputList() : array
    {
        $returnVal = array(
            'id',
            'name',
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
        $returnVal = array(
            array(
                'field'   => 'id',
                'label'   => 'ID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'name',
                'label'   => '名前',
                'rules'   => 'required'
            ),
        );
        return ($returnVal);
    }


}
