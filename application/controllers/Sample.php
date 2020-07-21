<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample extends CI_Controller {
/*
    ■機　能： 動作確認用画面処理
    ■概　要：
    ■更新日： 2019/12/23
    ■担　当： crew.miwa
    ■更新履歴：
        2019/12/23: 作成開始
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
    }
    // 共通テンプレート
    function sharedTemplate ($templateVal = "") {
        return $templateVal;
    }
    // TOP画面
    public function index()
    {
        $templateVal = "";
        // テンプレート読み込み
        $this->load->view('sample01', self::sharedTemplate($templateVal));
    }
}
