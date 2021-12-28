<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Worker extends MY_Controller
{
    /**
     * 作成者追加用画面処理
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
        $this->load->model('worker_model');
    }
    // 共通テンプレート
    public function sharedTemplate($returnVal = array())
    {
        // クラス定数をセット
        $returnVal['const'] = $this->jscss_lib->GetConstListAddSelName();

        return $returnVal;
    }
    // 登録画面
    public function index()
    {
        // FORM情報の確認
        $action = $this->input->post_get('action', true);
        $id = $this->input->post_get('id', true);
        // 追加処理
        if ($action == 'add') {
            // エラーチェックルールをセット
            $config = $this->worker_model->ConfigValues();
            $this->form_validation->set_rules($config);
            // バリデーション実行結果を取得
            $validFlg = $this->form_validation->run();
            if ($validFlg) {
                // 仮登録処理
                $this->worker_model->RegistAction($validFlg);
                // テンプレート読み込み
                redirect('worker');
            } else {
                $templateVal = $this->worker_model->TopTemplate($validFlg);
                // 入力テンプレート読み込み
                $this->load->view('worker', $templateVal);
            }
        } else {
            // テンプレート情報をセット
            $templateVal = $this->worker_model->TopTemplate();
            // テンプレート読み込み
            $this->load->view('worker', $templateVal);
        }
    }



    // 完了画面
    public function comp()
    {
        $templateVal = $this->worker_model->CompTemplate();
        // テンプレート読み込み
        $this->load->view('worker_comp', $templateVal);
    }



    // エラー画面
    public function error()
    {
        // テンプレート読み込み
        $this->load->view('worker_error');
    }


    // Ajax処理
    public function ajax()
    {
        // 返値を初期化
        $returnVal = array();
        $returnVal[Jscss_lib::KEY_AJAX_REACTION_FLG] = true;
        $returnVal[Jscss_lib::KEY_AJAX_REACTION]['ajax'] = 'TEST';

        // JSON形式で返す
        echo json_encode($returnVal);
    }
}
