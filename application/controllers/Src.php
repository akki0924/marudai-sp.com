<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Src extends CI_Controller {
/*
    ■機　能： ソース管理
    ■概　要： 
    ■更新日： 2018/11/06
    ■担　当： crew.miwa
    ■更新履歴：
        2018/11/06: 作成開始
*/
    // 初期ページ
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIST_COUNT = 50;
    // プルダウン初期情報をセット
    const NO_FIRST_WORD = "選択できません";
    
    // コンストラクタ
    public function __construct() {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();
/*
        // モデル呼出し
        $this->load->model('login_model');
        $this->load->model('admin_model');
        // 認証確認処理
        $this->login_model->login_check (login_model::AUTH_ADMIN);
*/
    }
    // 共通テンプレート
    function sharedTemplate ($templateVal = "")
    {
        // 各テンプレートをセット
        $templateVal['header_tpl'] = $this->load->view ( 'header', $templateVal );
        $templateVal['side_tpl'] = $this->load->view ( 'side', $templateVal );
        $templateVal['footer_tpl'] = $this->load->view ( 'footer', $templateVal );
        
        return $templateVal;
    }
    public function index()
    {
print "check!";
    }
    
    public function input()
    {
        // モデル呼出し
        $this->load->model('admin_model');
        $this->load->model('public_model');
        $this->load->model('login_model');
        $this->load->model('file_regist_model');
        
        // 一覧情報を取得
//        $templateVal['list'] = $this->config_model->all_values (true);
        
        // テンプレート読み込み
        $this->load->view('src_input', self::sharedTemplate($templateVal));
    }
    
/*
    // 商品登録・更新画面
    public function add()
    {
        // モデル呼出し
        $this->load->model('login_model');
        // ライブラリー呼出し
        $this->load->library('form_validation');
        // IDをセット
        $id = ($this->input->post_get('id', true) ? $this->input->post_get('id', true) : $this->item_model->create_id ());
        $templateVal['id'] = $id;
        
        // 共通エラー用設定
        $this->form_validation->set_error_delimiters('<div class="red">', '</div>');
        // フロー情報取得
        $action = $this->input->post_get('action', true);
        
        // 入力画面
        if ($action == "" || $action == "input")
        {
            // 登録情報をセット
            $templateVal['form'] = $this->item_model->detail_values ($id);
            // FORM情報をセット
            $form = $this->item_model->input_form_list();
            for ($i = 0, $n = count ($form); $i < $n; $i ++) {
//                $templateVal['form'][$form[$i]] = $this->input->post_get($form[$i], true);
                $form[$form[$i]] = $this->input->post_get($form[$i], true);
                $templateVal['form'][$form[$i]] = ($form[$form[$i]] != "" ? $form[$form[$i]] : (isset ($templateVal['form'][$form[$i]]) ? $templateVal['form'][$form[$i]] : ""));
            }
            
            // カテゴリーメニュー一覧
            $templateVal['category_list'] = $this->category_model->select_values (false, true);
            // サブメニュー一覧
            $templateVal['category_sub_list'] = $this->category_model->select_sub_values (true);
            // 項目一覧
            $templateVal['type_list'] = $this->type_model->select_values (true);
            // PDFファイルのセット有無確認
            $templateVal['form']['pdf_exists'] = $this->item_model->pdf_exists ($id);
            
            // テンプレート読み込み
            $this->load->view(Login_model::AUTH_ADMIN . '/item_add', self::sharedTemplate($templateVal));
        }
        // 確認画面
        else if ($action == "conf")
        {
            // FORM情報をセット
            $form = $this->item_model->input_form_list();
            for ($i = 0, $n = count ($form); $i < $n; $i ++) {
                $templateVal['form'][$form[$i]] = $this->input->post_get($form[$i], true);
            }
            
            // 検証
            $config = array_merge ($this->item_model->config_values_input(), self::_config_values_name($id), self::_config_values_pdf($id));
            $this->form_validation->set_rules($config);
            // エラー時
            if ($this->form_validation->run() == FALSE) {
                // カテゴリーメニュー一覧
                $templateVal['category_list'] = $this->category_model->select_values (false, true);
                // サブメニュー一覧
                $templateVal['category_sub_list'] = $this->category_model->select_sub_values (true);
                // 項目一覧
                $templateVal['type_list'] = $this->type_model->select_values (true);
                // PDFファイルのセット有無確認
                $templateVal['form']['pdf_exists'] = $this->item_model->pdf_exists ($id, true);
                
                // テンプレート読み込み
                $this->load->view(Login_model::AUTH_ADMIN . '/item_add', self::sharedTemplate($templateVal));
            }
            else {
                // hidden値をセット
                $templateVal['form_hidden'] = $templateVal['form'];
                
                // カテゴリーメニュー名
                $templateVal['form']['category_name'] = $this->category_model->name ($templateVal['form']['category_id'], true);
                // サブメニュー名
                $templateVal['form']['category_sub_name'] = $this->category_model->sub_name ($templateVal['form']['category_sub_id'], true);
                // 項目メニュー名
                $templateVal['form']['type_name'] = $this->type_model->name ($templateVal['form']['type_id'], true);
                // PDFファイルのセット有無確認
                $templateVal['form']['pdf_exists'] = $this->item_model->pdf_exists ($id, true);
                
                // テンプレート読み込み
                $this->load->view(Login_model::AUTH_ADMIN . '/item_conf', self::sharedTemplate($templateVal));
            }
            
        }
        // 完了画面
        else if ($action == "comp")
        {
            // FORM情報をセット
            $form = $this->item_model->input_form_list();
            for ($i = 0, $n = count ($form); $i < $n; $i ++) {
                $templateVal['form'][$form[$i]] = $this->input->post_get($form[$i], true);
            }
            // 検証
            $config = array_merge ($this->item_model->config_values_input(), self::_config_values_name($id), self::_config_values_pdf($id));
            $this->form_validation->set_rules($config);
            // エラー時
            if ($this->form_validation->run() == FALSE) {
            // FORM情報をセット
                // カテゴリーメニュー一覧
                $templateVal['category_list'] = $this->category_model->select_values (false, true);
                // サブメニュー一覧
                $templateVal['category_sub_list'] = $this->category_model->select_sub_values (true);
                // 項目一覧
                $templateVal['type_list'] = $this->type_model->select_values (true);
                // テンプレート読み込み
                $this->load->view(Login_model::AUTH_ADMIN . '/item_add', self::sharedTemplate($templateVal));
            }
            else {
                // DB登録更新処理
                $id = $this->item_model->regist($id);
                // テンプレート読み込み
                $this->load->view(Login_model::AUTH_ADMIN . '/item_comp', self::sharedTemplate($templateVal));
            }

        }
    }
    
    // 商品登録・更新画面
    public function edit()
    {}
    // ユーザー削除処理（論理削除）
    public function delete()
    {
        // FROM情報の確認
        $target_id = $this->input->post_get('id', true);
        
        // DB更新処理
        $this->item_model->delete ($target_id);
        
        // ページ遷移
        redirect('admin/item/edit');
    }
    // ファイル名一覧取得処理
    public function name_list()
    {
        // FROM情報の確認
        $category_id = $this->input->post_get('category_id', true);
        $category_sub_id = $this->input->post_get('category_sub_id', true);
        $type_id = $this->input->post_get('type_id', true);
        
        // ファイル名一覧
        $templateVal['name_list'] = $this->item_model->select_name_values ($category_id, $category_sub_id, $type_id, "", true);
        // 未選択一覧
        $templateVal['no_list'][''] = self::NO_FIRST_WORD;
        
        $returnVal = $this->load->view(Login_model::AUTH_ADMIN . '/item_ajax_name', $templateVal, true);
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // 選択ファイル情報を取得処理
    public function select_val()
    {
        // FROM情報の確認
        $id = $this->input->post_get('id', true);
        // 詳細情報をセット
        $returnVal = $this->item_model->detail_values ($id, true);
        // PDFファイルのセット有無確認
        $returnVal['pdf_exists'] = $this->item_model->pdf_exists ($id);
        
        // JSON形式で返す
        echo json_encode($returnVal);
    }
    // ユニークカラムに同一内容のエラー文
    function SameErrorValues ($id = "", $account, $email, $public) {
        $returnVal = "";
        
        $returnVal['acount'] = ($this->user_model->acount_same_check ($id, $account, $public) ? User_model::ERROR_SAME_STR : "");
        $returnVal['email'] = ($this->user_model->email_same_check ($id, $email, $public) ? User_model::ERROR_SAME_STR : "");
        $returnVal['error'] = ($returnVal['acount'] || $returnVal['email'] ? true : false);
        
        return $returnVal;
    }
    // ユニークカラムに同一内容のエラー文
    function SameErrorValuesEdit ($id = "", $account, $public) {
        $returnVal = "";
        
        $returnVal['acount'] = ($this->user_model->acount_same_check ($id, $account, $public) ? User_model::ERROR_SAME_STR : "");
        $returnVal['error'] = ($returnVal['acount'] ? true : false);
        
        return $returnVal;
    }

    // ファイル名
    public function _config_values_name ($id) {
        $returnVal[] = array(
            'field'   => 'name',
            'label'   => 'ファイル名',
            'rules'   => 'trim|required|callback__check_name[' . $id . ']'
        );
        return ($returnVal);
    }
    public function _check_name ($name, $id)
    {
        // 検証
        $this->load->library('form_validation');
        // それぞれの値が一致する場合
        // 登録有無の確認
        if (!$this->item_model->name_same_check($name, $id, true)) {
            // DBに未登録の為バリデーションOK
            return TRUE;
        }
        else {
            // エラーメッセージをセット
            $this->form_validation->set_message('_check_name', '入力されたファイル名はすでに登録されています。');
            // 値が不一致の為バリデーションNG
            return FALSE;
        }
    }
    // PDFファイル
    public function _config_values_pdf ($id) {
        $returnVal[] = array(
            'field'   => Item_model::SRC_FORM_NAME,
            'label'   => 'ファイル',
            'rules'   => 'callback__check_pdf[' . $id . ']'
        );
        return ($returnVal);
    }
    public function _check_pdf ($pdf, $id)
    {
        // 検証
        $this->load->library('form_validation');
        // 登録有無の確認
        if (!$this->item_model->do_tmp_upload(Item_model::SRC_FORM_NAME, $id)) {
            // DBに未登録の為バリデーションOK
            return TRUE;
        }
        // DBに登録済の為バリデーションNG
        $this->form_validation->set_message('_check_pdf', '%s は保存出来ませんでした。');
        return FALSE;
    }
*/
}
