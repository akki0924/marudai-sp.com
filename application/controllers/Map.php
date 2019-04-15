<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {
/*
    ■機　能： ログイン画面処理
    ■概　要： 
    ■更新日： 2017/10/12
    ■担　当： crew.miwa
    ■更新履歴：
        2017/10/12: 作成開始
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
        $this->load->view('map', self::sharedTemplate($templateVal));
    }
}
