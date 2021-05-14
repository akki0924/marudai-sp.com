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
 * @since 1.0.0     2021/05/14：新規作成
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
        $returnVal['select']['count'] = $this->pagenavi_lib->GetListCount()
        // FORM情報をセット
        $returnVal['action'] = $this->input->post_get('action', true);
        foreach ($this->FormDefaultList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }

        // ページ一覧用の情報を取得
        $returnVal['form']['select_count'] = ($returnVal['form']['select_count'] != '' ? $returnVal['form']['select_count'] : Pagenavi_lib::DEFAULT_LIST_COUNT);

        // WHERE情報をセット
        $whereSql = array();
        // キーワード
        if ($returnVal['form']['search_keyword'] != '') {
            $whereSql[] = Example_lib::MASTER_TABLE . " . name LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";
        }
        // 一覧表示数の取得
        $returnVal['count'] = $this->GetListCount($whereSql);
        // ページナビ情報を取得
        $returnVal['pager'] = $this->pagenavi_lib->GetValeus($returnVal['count'], $returnVal['form']['page'], $returnVal['form']['select_count']);
        // LIMIT情報をセット
        $limitSql['begin'] = ($returnVal['pager']['listStart'] - 1);
        $limitSql['row'] = $returnVal['form']['select_count'];
        // ORDER情報をセット
        $orderSql[0]['key'] = User_lib::MASTER_TABLE . ' . edit_date';
        $orderSql[0]['arrow'] = 'DESC';
        // 一覧情報を取得
        $returnVal['list'] = $this->GetList($whereSql, $orderSql, $limitSql);

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 詳細テンプレート情報を取得
     *
     * @param string $id：ID
     * @return array|null
     */
    public function DetailTemplate(string $id = '') : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // FORM情報
        $id = ($id ? $id : $this->input->post_get('id', true));
        // 情報の存在有無
        $exists = $this->reserve_lib->IdExists($id);
        $returnVal['exists'] = $exists;
        // 受注ID存在
        if ($exists) {
            // 受注詳細情報を取得
            $returnVal['form'] = $this->reserve_lib->GetDetailValues($id);
        }

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 入力・確認テンプレート情報を取得
     *
     * @param bool $validFlg：バリデーション結果
     * @return array|null
     */
    public function InputTemplate(bool $validFlg = false) : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報をセット
        $whereSql = array();
        // 初期画面
        if (
            $action == '' &&
            $exists
        ) {
            // ユーザーIDが存在
            if ($exists) {
                // 受注詳細情報を取得
                $returnVal['form'] = $this->reserve_lib->GetDetailValues($id);
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
                // 選択情報の表示名をセット
                $returnVal['form']['pref_name'] = $this->user_lib->GetPrefName($returnVal['form']['pref_id']);
                $returnVal['form']['status_name'] = $this->user_lib->GetStatusName($returnVal['form']['status']);
            }
        }
        // 選択情報をセット
        $returnVal['select']['pref'] = $this->user_lib->GetPrefList();
        $returnVal['select']['status'] = $this->user_lib->GetStatusList();

        return $this->sharedTemplate($returnVal);
    }


    /**
     * 完了テンプレート情報を取得
     *
     * @param string $id：ID
     * @return array|null
     */
    public function CompTemplate(string $id = '') : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // FORM情報
        $id = ($id ? $id : $this->input->post_get('id', true));
        // 情報の存在有無
        $returnVal['exists'] = $this->reserve_lib->IdExists($id);

        return $this->sharedTemplate($returnVal);
    }


    /**
     * データ削除処理
     *
     * @param string $id：ID
     * @return bool|null
     */
    public function DelActionTemplate(string $id = '') : ?bool
    {
        // 返値を初期化
        $returnVal = false;
        // FORM情報をセット
        $id = $this->input->post_get('id', true);
        // 削除処理
        $returnVal = $this->genre_lib->SelectDelete($id);

        return $returnVal;
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
