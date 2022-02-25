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

    // ダミー画面
    public function index()
    {
        // リダイレクト処理
        redirect('/');
    }


    // 登録画面
    public function input($placeCode = "")
    {
        // 秤バーコードチェック
        $this->keiryo_model->CheckPlaceCode($placeCode);
        // FORM情報の確認
        $action = $this->input->post_get('action', true);
        $id = $this->input->post_get('id', true);
        if ($action == 'add') {
            // エラーチェックルールをセット
            $config = $this->keiryo_model->ConfigInputValues($placeCode);
            // バリデーションのデータを再セット（調整中）
            $this->keiryo_model->SetValidData();
            // バリデーションにルールをセット
            $this->form_validation->set_rules($config);
            // バリデーション実行結果を取得
            $validFlg = $this->form_validation->run();
            if ($validFlg) {
                // 登録処理
                $this->keiryo_model->RegistAction($placeCode);
                // リダイレクト処理
                redirect('keiryo/input' . ($placeCode ? '/' . $placeCode : ''));
            } else {
                $templateVal = $this->keiryo_model->InputTemplate($placeCode);
                // 入力テンプレート読み込み
                $this->load->view('keiryo', $templateVal);
            }
        } else {
            // テンプレート情報をセット
            $templateVal = $this->keiryo_model->InputTemplate($placeCode);
            // テンプレート読み込み
            $this->load->view('keiryo', $templateVal);
        }
    }


    // 完了処理
    public function comp($placeCode = "")
    {
        // 秤バーコードチェック
        $this->keiryo_model->CheckPlaceCode($placeCode);

        // 登録処理
        $templateVal = $this->keiryo_model->RegistAction($placeCode);

        // テンプレート読み込み
        $this->load->view('keiryo_comp', $templateVal);
    }



    // エラー画面
    public function error()
    {
        // テンプレート読み込み
        $this->load->view('keiryo_error');
    }


    // バーコード読取り処理（Ajax）
    public function ajax_code()
    {
        // JSON形式で返す
        echo json_encode($this->keiryo_model->GetAjaxCodeAction());
    }


    // リスト更新処理（Ajax）
    public function ajax_list()
    {
        // JSON形式で返す
        echo json_encode($this->keiryo_model->GetAjaxListAction());
    }


    // ページ専用JSファイル
    public function js($placeCode = "")
    {
        // テンプレート情報をセット
        $templateVal = $this->keiryo_model->InputTemplate($placeCode);
        // テンプレート読み込み
        $this->load->view(Base_lib::JS_DIR . Base_lib::WEB_DIR_SEPARATOR . 'keiryo', $templateVal);
    }
}
