<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form extends MY_Controller
{
    /*
        ■機　能： FORM関連処理
        ■概　要：
        ■更新日： 2021/02/26
        ■担　当： crew.miwa
        ■更新履歴：
            2021/02/26: 作成開始
    */
    // コンストラクタ
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // ライブラリー読込み
        $this->load->library('jscss_lib');
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
