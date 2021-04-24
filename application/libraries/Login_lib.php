<?php
    /*
    ■機　能： ログイン情報用ライブラリ
    ■概　要： ログイン情報用関連全般
    ■更新日： 2019/04/16
    ■担　当： crew.miwa

    ■更新履歴：
     2019/04/16: 作成開始

    */

class Login_lib
{
    // 定数
    const LIBLARY_DIR = APPPATH . Base_lib::LIBRARY_DIR . DIRECTORY_SEPARATOR;    // ライブラリ
    const USER_LIB_NAME = 'Base_lib::ACCESS_ADMIN_DIR';
    // メンバー変数
    protected $CI;              // スーパーオブジェクト割当用
    private $targetKey;         // 対象キー（ディレクトリー名、DBテーブル名で使用）
    private $targetLib;         // 読込みライブラリ代入先
    private $targetTable;       // 対象テーブル
    private $targetAccount;     // 対象アカウント（カラム名）
    private $targetPassword;    // 対象パスワード（カラム名）
    /*====================================================================
        コントラクト
    */
    public function __construct($params = array())
//    public function __construct( $params )
//    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // ライブラリー読込み
        $this->CI->load->library('session');
        $this->CI->load->library('session_lib');
        // 対象キーが引数にセットされている場合
        if (isset($params['key']) && $params['key'] != '') {
            // 初期情報セットする
            $this->SetSource($params['key']);
        }
    }
    /*====================================================================
        関数名： SetSource
        概　要： 対象配列をメンバー変数にセット(小文字に変換)
    */
    public function SetSource($targetKey = '')
    {
        // 返り値を初期化
        $returnVal = array();

        // 対象キー
        $this->SetTargetKey($targetKey);
        // ライブラリー
        $this->SetLibrary();
        // 対象ライブラリーがセットされている場合
        if ($this->TargetLibExists()) {
            // テーブル
            $this->SetTable();
            // アカウント
            $this->SetAccount();
            // パスワード
            $this->SetPassword();
        }
    }
    /*====================================================================
        関数名： LoginAction
        概　要： ログイン情報実行処理（SESSION内容登録）
    */
    public function LoginAction($account, $passowrd)
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }

        // 認証確認処理
        return ($this->Execute($account, $passowrd, true) ? true : false);
    }
    /*====================================================================
        関数名： LoginCheck
        概　要： ログイン情報処理（SESSION情報確認）
    */
    public function LoginCheck()
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }
        // 既に認証済みの有無を返す
        return ($this->Execute('', '', true) ? true : false);
    }
    /*====================================================================
        関数名： TargetKeyExists
        概　要： 対象キーがセットされているかどうか
    */
    public function TargetKeyExists()
    {
        return ($this->targetKey != '' ? true : false);
    }
    /*====================================================================
        関数名： TargetLibExists
        概　要： 対象ライブラリーがセットされているかどうか
    */
    public function TargetLibExists()
    {
        return ($this->targetLib != '' ? true : false);
    }
    /*====================================================================
        関数名： Execute
        概　要： ログイン認証処理
    */
    public function Execute($account = "", $password = "", $public = false)
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }
        // 返り値の初期値
        $returnVal = false;
        // ユーザーライブラリーの存在確認
        $userLibExists = $this->CI->file_lib->FileExists(Base_lib::MASTER_DIR . '/' . ucfirst(self::USER_LIB_NAME) . '.php');
        // ユーザーライブラリーが存在
        if ($userLibExists) {
            // ライブラリー読込み
            $this->CI->load->library(Base_lib::MASTER_DIR . '/' . self::USER_LIB_NAME);
        }

        // 認証済（SESSION情報の登録、入力情報の有無）
        if (
            $this->CI->session->has_userdata($this->targetKey . "_" . "id") &&
            $this->CI->session->has_userdata($this->targetKey . "_" . "success") &&
            $account == "" &&
            $password == ""
        ) {
            // SQLクエリ
            $query = $this->CI->db->query("
                SELECT COUNT(*) AS count FROM " . $this->targetTable . "
                WHERE (
                    id = '" . Base_lib::AddSlashes($this->GetSessionId()) . "'
                    " . ($public ? " AND status >= " . Base_lib::STATUS_ENABLE : "") . "
                )
            ");
            // 結果が、空でない場合
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    if ($row->count > 0) {
                        $returnVal = true;
                    } else {
                        // SESSION情報クリア
                        $this->ClearSessionValues();
                    }
                    break;
                }
            } else {
                // SESSION情報クリア
                $this->ClearSessionValues();
            }
        }
        // 未認証
        else {
            // SQLクエリ
            $query = $this->CI->db->query("
                SELECT id FROM " . $this->targetTable . "
                WHERE (
                    " . $this->targetAccount . "  = '" . Base_lib::AddSlashes($account) . "' AND
                    " . $this->targetPassword . "  = '" . Base_lib::AddSlashes($password) . "'
                    " . ($userLibExists && $this->GetTable() == User_lib::MASTER_TABLE ? ' AND regist_type=' . User_lib::ID_REGIST_TYPE_REGULAR  : '') . "
                    " . ($public ? " AND status >= " . Base_lib::STATUS_ENABLE : "") . "
                )
            ");
            // 結果が、空でない場合
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    // SESSION登録処理
                    $this->SetSessionValues($row->id);
                    $returnVal = true;
                }
            } else {
                $this->ClearSessionValues();
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： SetSessionValues
        概　要： SESSION情報を登録
    */
    public function SetSessionValues($id)
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }

        $values = array(
            $this->targetKey . "_" . "id"         => $id,
            $this->targetKey . "_" . "last_login" => date("Y-m-d H:i:s"),
            $this->targetKey . "_" . "success"    => true
        );
        return $this->CI->session->set_userdata($values);
    }
    /*====================================================================
        関数名： GetSessionValues
        概　要： SESSION情報を取得
    */
    public function GetSessionValues()
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }

        $values = array(
            $this->targetKey . "_" . "id"          => $this->CI->session->userdata($this->targetKey . "_" . "id"),
            $this->targetKey . "_" . "last_login"  => $this->CI->session->userdata($this->targetKey . "_" . "last_login"),
            $this->targetKey . "_" . "success"     => $this->CI->session->userdata($this->targetKey . "_" . "success")
        );
        return $values;
    }
    /*====================================================================
        関数名： GetSessionId
        概　要： SESSION情報IDを取得
    */
    public function GetSessionId()
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }

        return $this->CI->session->userdata($this->targetKey . "_" . "id");
    }
    /*====================================================================
        関数名： ClearSessionValues
        概　要： SESSION情報を削除
    */
    public function ClearSessionValues()
    {
        // 対象キーが未セットの場合、関数終了
        if (! $this->TargetKeyExists()) {
            return false;
        }

        $values = array(
            $this->targetKey . "_" . "id",
            $this->targetKey . "_" . "last_login",
            $this->targetKey . "_" . "success",
        );
        return $this->CI->session->unset_userdata($values);
    }
    /*====================================================================
        関数名： SetTargetKey
        概　要： 対象キーをセット
    */
    public function SetTargetKey($targetKey = '')
    {
        // 対象キーがセットされていない場合、セット
        if (! $this->TargetKeyExists()) {
            $this->targetKey = $targetKey;
        }
    }
    /*====================================================================
        関数名： SetLibrary
        概　要： 対象キーがセットされているかどうか
    */
    public function SetLibrary()
    {
        // 対象ライブラリー名をセット
        $lib_name = $this->targetKey . '_lib';
        // 対象ライブラリーが存在
        if (file_exists(self::LIBLARY_DIR . ucfirst($lib_name) . '.php')) {
            // 対象ライブラリーを読込み
            $this->CI->load->library(ucfirst($lib_name));
        } else {
            // 対象ライブラリーを読込み
            $this->CI->load->library(Base_lib::MASTER_DIR . '/' . ucfirst($lib_name));
        }
        // クラス変数に代入
        $this->targetLib = $this->CI->{$lib_name};
    }
    /*====================================================================
        関数名： SetTable
        概　要： 対象テーブル情報をセット
    */
    public function SetTable()
    {
        // 対象テーブルをセット
        $this->targetTable = $this->targetLib->GetMasterTable();
    }
    /*====================================================================
        関数名： GetTable
        概　要： 対象テーブル情報を取得
    */
    public function GetTable()
    {
        return $this->targetTable;
    }
    /*====================================================================
        関数名： SetAccount
        概　要： 対象アカウント情報をセット
    */
    public function SetAccount()
    {
        // 対象アカウントをセット
        $this->targetAccount = $this->targetLib->GetAccount();
    }
    /*====================================================================
        関数名： GetAccount
        概　要： 対象アカウント情報を取得
    */
    public function GetAccount()
    {
        return $this->targetAccount;
    }
    /*====================================================================
        関数名： SetPassword
        概　要： 対象パスワード情報をセット
    */
    public function SetPassword()
    {
        // 対象パスワードをセット
        $this->targetPassword = $this->targetLib->GetPassword();
    }
    /*====================================================================
        関数名： GetAccount
        概　要： 対象パスワード情報を取得
    */
    public function GetPassword()
    {
        return $this->targetPassword;
    }
}
