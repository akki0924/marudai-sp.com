<?php
/**
 * 共通モデル
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/06/16：新規作成
 */
class MY_Model extends CI_Model
{
    const DEFAULT_LIST_COUNT = 200;
    const FIRST_MSG = '検索項目を選択してください。';
    const NO_LIST_MSG = '一覧リストが見つかりません。';
    // 並び順用文字列
    const SORT_COLUMN = 'sort_id';
    const SORT_ARROW = 'desc';

    /**
     * コントラクト
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * バリデーションへ追加用の項目を追加
     *
     * @return array
     */
    public function ValidSetData() : ?array
    {
        // POSTデータを取得
        foreach ($_POST as $key => $val) {
            $formKeys[$key] = $val;
        }
        // GETデータを取得
        foreach ($_GET as $key => $val) {
            $formKeys[$key] = $val;
        }
        // POST・GETデータをXSSフィルタリングで再取得
        foreach ($formKeys as $key => $value) {
            $addForm[$key] = $this->input->post_get($key, true);
        }
        // 追加フォームをセット
        $addForm[Base_lib::VALID_ADD_NAME] = '1';
        // バリデーション用データを再セット
        $this->form_validation->set_data($addForm);
    }
}
