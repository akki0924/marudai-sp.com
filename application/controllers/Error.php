<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends MY_Controller {
/*
    ■機　能：
    ■概　要：
    ■更新日： 2017/01/14
    ■担　当： crew.miwa
    ■更新履歴：
        2017/01/14: 作成開始
*/
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
        // モデル呼出し
        $this->load->model('login_model');
    }
    // 共通テンプレート
    function sharedTemplate ($templateVal = "") {
        return $templateVal;
    }
    // エラー処理
    public function index()
    {
        // SESSION情報を削除
        $this->login_model->clear_values (login_model::AUTH_OWNER);
        // テンプレート値をセット
        $templateVal['action'] = "top";
        $templateVal['title'] = "指定された情報にアクセスできません。";
        $templateVal['body'] = "恐れ入りますが、再度TOPページよりアクセスしてください。";
        $templateVal['button_str'] = "TOPページに戻る";
        // テンプレート読み込み
        $this->load->view('error', self::sharedTemplate($templateVal));
    }
    // エラー処理（ログアウト）
    public function logout()
    {
        // SESSION情報を削除
        $this->login_model->clear_values (login_model::AUTH_OWNER);
        // テンプレート値をセット
        $templateVal['action'] = "index";
        $templateVal['title'] = "指定された情報にアクセスできません。";
        $templateVal['body'] = "恐れ入りますが、再度TOPページよりアクセスしてください。";
        $templateVal['button_str'] = "TOPページに戻る";
        // テンプレート読み込み
        $this->load->view('error', self::sharedTemplate($templateVal));
    }
    // エラー処理（IDエラー）
    public function not_id()
    {
        // テンプレート値をセット
        $templateVal['action'] = "top";
        $templateVal['title'] = "指定された情報にアクセスできません。";
        $templateVal['body'] = "恐れ入りますが、再度TOPページよりアクセスしてください。";
        $templateVal['button_str'] = "TOPページに戻る";
        // テンプレート読み込み
        $this->load->view('error', self::sharedTemplate($templateVal));
    }
    // エラー処理（登録時間切れ）
    public function time_out()
    {
        // テンプレート値をセット
        $templateVal['action'] = "top";
        $templateVal['title'] = "登録可能時間が経過しました。";
        $templateVal['body'] = "恐れ入りますが、再度TOPページよりアクセスしてください。";
        $templateVal['button_str'] = "TOPページに戻る";
        // テンプレート読み込み
        $this->load->view('error', self::sharedTemplate($templateVal));
    }
    // エラー処理（404エラー）
    public function error_404()
    {
        // SESSION情報を削除
//        $this->login_model->clear_values (login_model::AUTH_OWNER);
        // テンプレート値をセット
        $templateVal['action'] = "index";
        $templateVal['title'] = "指定された情報にアクセスできません。";
        $templateVal['body'] = "恐れ入りますが、再度TOPページよりアクセスしてください。";
        $templateVal['button_str'] = "TOPページに戻る";
        // テンプレート読み込み
        $this->load->view('error', self::sharedTemplate($templateVal));
    }
}
