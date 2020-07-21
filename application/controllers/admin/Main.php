<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
/*
    ■機　能： Topページ画面処理
    ■概　要： 
    ■更新日： 2019/01/18
    ■担　当： crew.miwa
    ■更新履歴：
        2019/01/18: 作成開始
        2017/01/06: ログイン機能追加
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
    }
    // 共通テンプレート
    function sharedTemplate ( $templateVal = "" )
    {
        // 各テンプレートをセット
        return $templateVal;
    }
    // TOP画面
    public function index()
    {

//        $this->load->view( Base_lib::ADMIN_DIR . '/main', $this->sharedTemplate ( $templateVal ) );
        $this->load->view( Base_lib::ADMIN_DIR . '/main' );
    }
    // バリデーション内容
    public function config_values ()
    {
        $returnVal[] = array(
            'field'   => 'no',
            'label'   => 'No.',
            'rules'   => 'required|is_natural'
        );
        $returnVal[] = array(
            'field'   => 'start',
            'label'   => '日付範囲 開始日',
            'rules'   => 'required'
        );
        $returnVal[] = array(
            'field'   => 'end',
            'label'   => '日付範囲 終了日',
            'rules'   => 'required'
        );
        return ($returnVal);
    }
}
