<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create extends MY_Controller
{
    /*
        ■機　能： 自動実行画面処理
        ■概　要：
        ■更新日： 2021/06/30
        ■担　当： crew.miwa
        ■更新履歴：
            2021/02/26：作成開始
            2021/06/30：自動実行結果ログを画面上に表示
    */
    // コンストラクタ
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // ライブラリー読込み
        $this->load->library('jscss_lib');
        $this->load->library('create_lib');
    }
    // 共通テンプレート
    public function sharedTemplate($returnVal = array())
    {
        // クラス定数をセット
        $returnVal['const'] = $this->jscss_lib->GetConstListAddSelName();
        return $returnVal;
    }
    // TOP画面
    public function index()
    {
        // テンプレート読み込み
        $this->load->view('sample', $this->sharedTemplate());
    }
    // ファイル生成テスト
    public function admin()
    {
        // ファイル生成
        $logId = $this->create_lib->CreateAdmin();
        // 実行結果を取得
        $returnVal = $this->create_lib->GetLogDetailValues($logId);
        echo $this->create_lib->GetLogDataDisp($returnVal);
    }

    // ファイル生成テスト（モデル版）
    public function create_model()
    {
        // ファイル生成
        $this->create_lib->CreateModels();
        print "ok";
    }
    // ファイル生成テスト
    public function create()
    {
        // ファイル生成
        $this->create_lib->CreateLibraries();
        print "ok";
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

    // JSファイル書出し
    public function js()
    {
        // JSファイル書出し
        $this->jscss_lib->CreateJs($this->load->view('js/create_js', $this->sharedTemplate(), true));
    }
    // CCSファイル書出し
    public function css()
    {
        // CCSファイル書出し
        $this->jscss_lib->CreateCss($this->load->view('css/create_css', $this->sharedTemplate(), true));
    }
}
