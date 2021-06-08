<?php
    /*
    ■機　能： 管理者情報用ライブラリ
    ■概　要： 管理者情報用関連全般
    ■更新日： 2019/01/17
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/17: 作成開始

    */

class Admin_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_admin";
    // キー名
    const KEY_NAME = "admin";
    // カラム名
    const ACCOUNT_COLUMN = "account";   // アカウント
    const PASSWORD_COLUMN = "password"; // パスワード

    // 権限
    const ID_AUTH_NORMAL = 1;
    const ID_AUTH_ADMIN = 2;
    const NAME_AUTH_NORMAL = '一般';
    const NAME_AUTH_ADMIN = '管理者';

    // ID生成文字列数
    const ID_STR_NUM = 10;

    // パスワード
    const PASSWORD_STR_NUM_MIN = 6;     // 最小文字列
    const PASSWORD_STR_NUM_MAX = 12;    // 最大文字列

    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // アクセス用のディレクトリ以外
        if (!$this->SameAccessDirExists()) {
            show_404();
        }
    }
    /*====================================================================
        関数名： GetDetailValues
        概　要： データ一覧を取得
    */
    public function GetDetailValues($id = "", $public = false)
    {
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . account,
                " . self::MASTER_TABLE . " . password,
                " . self::MASTER_TABLE . " . authority,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = '" . Base_lib::AddSlashes($id) . "'
            " . ($public ? " AND " . self::MASTER_TABLE . " . status >= " . Base_lib::STATUS_ENABLE : "") . "
            );
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $result_list = $query->result_array();
            foreach ($result_list[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        } else {
            $returnVal = "";
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetList
        概　要 : 一覧を取得
        引　数 : $whereSql : WHERE情報を配列形式で
                 $orderSql : ORDER情報を配列 + 連想形式（key : 対象カラム, arrow : 矢印）
                 $limitSql : LIMIT情報を配列形式（begin : 開始行, row : 件数）
                 $public : ステータスフラグ
    */
    public function GetList($whereSql = '', $orderSql = '', $limitSql = '', $public = false)
    {
        // 返値を初期化
        $returnVal = array();
        // WHERE情報を再セット
        if (! is_array($whereSql)) {
            $whereSql = [];
        }
        // ORDER情報を再セット
        if (! is_array($orderSql)) {
            $orderSql = array();
            $orderSql[0]['key'] = self::MASTER_TABLE . ' . name';
            $orderSql[0]['arrow'] = 'ASC';
        }
        // ORDER文を生成
        $orderSqlVal = 'ORDER BY';
        for ($i = 0, $n = count($orderSql); $i < $n; $i ++) {
            $orderSqlVal .= ' ' . $orderSql[$i]['key'] . ' ' . $orderSql[$i]['arrow'];
        }
        // ステータス情報
        if ($public) {
            $whereSql[] = self::MASTER_TABLE . " . status >= " . Base_lib::STATUS_ENABLE;
        }
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . account,
                " . self::MASTER_TABLE . " . authority
            FROM " . self::MASTER_TABLE . "
            " . (isset($whereSql) && count($whereSql) > 0 ? (" WHERE ( " . @implode(" AND ", $whereSql)) . " ) " : "") . "
            " . $orderSqlVal . "
            " . (isset($limitSqlVal) ? $limitSqlVal : '') . ";
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $returnVal = $query->result_array();
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetNameList
        概　要： データ一覧を取得
    */
    public function GetNameList($public = false)
    {
        return $this->CI->db_lib->GetSelectValues(self::MASTER_TABLE, 'name', $public);
    }
    /*====================================================================
        関数名： GetName
        概　要： 名前を取得
    */
    public function GetName($id, $public = false)
    {
        return $this->CI->db_lib->GetValue(self::MASTER_TABLE, 'name', $id, 'id', $public);
    }
    /*====================================================================
        関数名： GetMasterTable
        概　要： マスターテーブルを取得
    */
    public function GetMasterTable()
    {
        return self::MASTER_TABLE;
    }
    /*====================================================================
        関数名： GetAccount
        概　要： アカウント（カラム名）を取得
    */
    public function GetAccount()
    {
        return self::ACCOUNT_COLUMN;
    }
    /*====================================================================
        関数名： GetPassword
        概　要： パスワード（カラム名）を取得
    */
    public function GetPassword()
    {
        return self::PASSWORD_COLUMN;
    }
    /*====================================================================
        関数名： IdExists
        概　要： IDが存在するかどうか
    */
    public function IdExists($id, $public = false)
    {
        return $this->CI->db_lib->ValueExists(self::MASTER_TABLE, $id, 'id', $public);
    }
    /*====================================================================
        関数名 : AccountSameExists
        概　要 : アカウントが対象ID以外に同じ値が存在するかどうか
        引　数 : $account : アカウント
                 $id : ID
                 $public : ステータスフラグ
    */
    public function AccountSameExists($account, $id = '', $public = false)
    {
        return $this->CI->db_lib->SameExists(self::MASTER_TABLE, $account, 'account', $id, 'id', $public);
    }
    /*====================================================================
        関数名： LoginAction
        概　要： ログイン処理
    */
    public function LoginAction($account, $password)
    {
        return $this->CI->login_model->execute(Login_model::AUTH_ADMIN, true, $account, $password);
    }
    /*====================================================================
        関数名 : CreateId
        概　要 : IDを生成
        引　数 : $public : ステータスフラグ
    */
    public function CreateId($public = false)
    {
        // 未登録のランダム文字列を生成
        return $this->CI->db_lib->CreateStr(self::MASTER_TABLE, 'id', self::ID_STR_NUM, $public);
    }
    /*====================================================================
        関数名： RegistData
        概　要： DB登録・更新
    */
    public function RegistData($registData = '', $id = '')
    {
        // 返り値をセット
        $returnVal = false;
        // 配列形式の確認
        if (is_array($registData)) {
            // 管理者IDが登録されているか
            if ($this->IdExists($id, true)) {
                // 登録情報にIDをセット
                $registData['id'] = $id;
                // 更新処理
                $returnVal = $this->CI->db_lib->Update(self::MASTER_TABLE, $registData, $id);
            } else {
                // IDが未セットの場合、IDを生成
                if (! isset($registData['id']) || $registData['id'] == '') {
                    $registData['id'] = $this->CreateId();
                }
                // 新規作成
                $returnVal = $this->CI->db_lib->Insert(self::MASTER_TABLE, $registData);
            }
            // 登録成功の場合、IDを返す
            if ($returnVal) {
                $returnVal = $registData['id'];
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： Delete
        概　要： DB削除（論理）
    */
    public function Delete()
    {
        $id = $this->CI->input->get_post('id', true);
        return $this->CI->db_lib->Delete(self::MASTER_TABLE, true, $id);
    }
    /*====================================================================
        関数名 : GetAuthList
        概　要 : 権限一覧を取得
    */
    public function GetAuthList()
    {
        $returnVal[ self::ID_AUTH_NORMAL ] = self::NAME_AUTH_NORMAL;
        $returnVal[ self::ID_AUTH_ADMIN ] = self::NAME_AUTH_ADMIN;

        return $returnVal;
    }
    /*====================================================================
        関数名 : GetAuthName
        概　要 : 権限名を取得
        引　数 : $auth : 対象権限
    */
    public function GetAuthName($auth)
    {
        // 一覧リストを取得
        $list = $this->GetAuthList();

        return $list[ $auth ];
    }
    /*====================================================================
        関数名 : AuthExists
        概　要 : 権限が存在するかどうか
        引　数 : $auth : 対象権限
    */
    public function AuthExists($auth)
    {
        // 掲載カテゴリ一覧を取得
        $targetList = $this->GetAuthList();

        return (isset($targetList[ $auth ]) ? true : false);
    }
    /*====================================================================
        関数名 : SameAccessDirExists
        概　要 : 管理ディレクトリと別にアクセス用ディレクトリが存在するかどうか
    */
    public function SameAccessDirExists()
    {
        // 返値を初期化
        $returnVal = false;
        if (Base_lib::ACCESS_ADMIN_DIR) {
            $targetStr = substr($_SERVER['REQUEST_URI'], (strlen(D_ROOT)));
            $targetArray = explode('/', $targetStr);

            if ($targetArray[0] == Base_lib::ACCESS_ADMIN_DIR) {
                $returnVal = true;
            }
        }
        return $returnVal;
    }
}
