<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
/*
    ■機　能： エラーページ画面処理
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
    // TOP画面
    public function index()
    {
        $templateVal = $this->main_model->template_top();
        $this->load->view('main', $templateVal);
    }
    // Form値をセット
    function FormList () {
        $returnVal[] = 'site';
        $returnVal[] = 'manager';
        $returnVal[] = 'password';
        
        // submitボタンを追加
        $returnVal[] = 'submit_btn';
        return $returnVal;
    }
}
