<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends MY_Controller
{
    /**
     * TOP用画面処理
     *
     * @author a.miwa <miwa@ccrw.co.jp>
     * @version 0.0.1
     * @since 0.0.1     2021/12/22：新規作成
     */

    // コンストラクタ
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // モデル呼出し
        $this->load->model('top_model');
    }
    // 共通テンプレート
    public function sharedTemplate($templateVal = "")
    {
        return $templateVal;
    }
    // TOP画面
    public function index()
    {
        // テンプレート情報をセット
        $templateVal = $this->top_model->TopTemplate();
        // テンプレート読み込み
        $this->load->view('index', $templateVal);
    }
}
