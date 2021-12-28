<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keiryo extends MY_Controller
{
    /**
     * 測量機バーコードスキャン用画面処理
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
        $this->load->model('keiryo_model');
    }
    // 共通テンプレート
    public function sharedTemplate($returnVal = array())
    {
        // クラス定数をセット
        $returnVal['const'] = $this->jscss_lib->GetConstListAddSelName();

        return $returnVal;
    }

    // 登録画面
    public function index($placeId = "")
    {
        // 場所IDチェック
        $this->keiryo_model->CheckPlaceId($placeId);
        // FORM情報の確認
        $action = $this->input->post_get('action', true);
        $id = $this->input->post_get('id', true);
        if ($action == 'add') {
            // エラーチェックルールをセット
            $config = $this->keiryo_model->ConfigInputValues();
            // バリデーションのデータを再セット（調整中）
            $this->keiryo_model->SetValidData();
            // バリデーションにルールをセット
            $this->form_validation->set_rules($config);
            // バリデーション実行結果を取得
            $validFlg = $this->form_validation->run();
            if ($validFlg) {
                // 登録処理
                $this->keiryo_model->RegistAction($validFlg);
                // テンプレート読み込み
                redirect('keiryo/comp' . ($id ? '/' . $id : ''));
            } else {
                $templateVal = $this->keiryo_model->InputTemplate($validFlg);
                // 入力テンプレート読み込み
                $this->load->view('keiryo', $templateVal);
            }
        } else {
            // テンプレート情報をセット
            $templateVal = $this->keiryo_model->InputTemplate();
            // テンプレート読み込み
            $this->load->view('keiryo', $templateVal);
        }
    }



    // 完了画面
    public function comp()
    {
        $templateVal = $this->keiryo_model->CompTemplate();
        // テンプレート読み込み
        $this->load->view('keiryo_comp', $templateVal);
    }



    // エラー画面
    public function error()
    {
        // テンプレート読み込み
        $this->load->view('keiryo_error');
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
