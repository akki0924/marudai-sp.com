<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 初期設定
 */
// 言語を日本語に設定
mb_language('Ja');

/**
 * ベース用マスタ処理プログラム
 *
 * 初期設定定数、及び継承元ライブラリ
 *
 * @author akki.m
 * @version 1.1.5
 * @since 1.0.0     2015/06/23  作成開始
 * @since 1.0.6     2020/01/24  各ディレクトリ名のメンバー定数追加
 * @since 1.1.0     2021/06/02  各マスタライブラリ継承用の関数、メンバー変数の追加
 * @since 1.1.1     2021/10/22  DB登録処理関数のバグを微修正
 * @since 1.1.2     2021/11/24  SiteHost関数の取得方法を定数に変更
 * @since 1.1.3     2022/01/06  細かいバグの修正
 * @since 1.1.4     2022/01/06  登録処理関数のID生成処理で文字列数が未指定の場合、初期値を利用する。および細かいバクの修正
 * @since 1.1.5     2022/02/04  コンソールログ表示関数でメソッドがJS/CSSを場外するインスタンス関数（ConsoleLogInst）を追加
 */
class Base_lib
{
    /**
     * const
     */
    // サイト情報
    const SITE_TITLE_NAME = "サンプルサイト";
    const SITE_DOMAIN = "○○○.com";
    const COPYRIGHT_NAME = "beta";

    // メール情報
    const ADMIN_MAIL = "miwa@ccrw.co.jp";

    // 各ページ名
    const PUBLIC_MAIN_PAGE = "main";                        // 表メインページ
    const OWNER_MAIN_PAGE = "main";                         // オーナーメインページ
    const ADMIN_MAIN_PAGE = "main";                         // 管理メインページ

    // 各ディレクトリ名
    const ACCESS_ADMIN_DIR = "adminc";                      // 管理（アクセス用）別途config/routes.phpで別途設定が必要
    const PUBLIC_DIR = "public";                            // 表
    const OWNER_DIR = "owner";                              // オーナー
    const ADMIN_DIR = "admin";                              // 管理
    const MASTER_DIR = "master";                            // マスタディレクトリ
    const CONTROLLER_DIR = "controllers";                   // コントローラディレクトリ
    const VIEW_DIR = "views";                               // ビューディレクトリ
    const MODEL_DIR = "models";                             // モデルディレクトリ
    const LIBRARY_DIR = "libraries";                        // ライブラリーディレクトリ
    const JS_DIR = "js";                                    // JSディレクトリ
    const CSS_DIR = "css";                                  // CSSディレクトリ
    const JSON_DIR = "json";                                // JSONディレクトリ
    const WEB_DIR_SEPARATOR = '/';                          // ディレクトリー区切り文字列（WEB）

    // 読込みjqueryファイル名
    const JQUERY_FILE = "jquery-3.5.0.min.js";
    const JQUERY_UI_JS_FILE = "jquery-ui-1.12.1.min.js";
    const JQUERY_UI_CSS_FILE = "jquery-ui-1.12.1.min.css";

    // ステータス
    const STATUS_ENABLE = 1;                                // 有効
    const STATUS_TEMP = 0;                                  // 保留
    const STATUS_DISABLE = -1;                              // 無効

    // 各名称
    const NAME_SUBMIT_BTN = 'submit_btn';                   // サブミットボタン

    // プルダウン無選択項目
    const DEFAULT_SELECT_FIRST_WORD = "▼選択して下さい";

    // 一覧が存在しない際のメッセージ
    const DEFAULT_NO_LIST_MSG = "NO LIST";

    // アップロード
    const SRC_DIR = 'src';                                  // アップロードディレクトリ
    const SRC_TEMP_DIR = 'tmp';                             // アップロード一時ディレクトリ

    // 画像
    const IMG_DIR = "images";                               // 画像ディレクトリ

    // バリデーション
    const VALID_SEPARATE_STR = '|';                         // バリデーションの分割用文字列
    const VALID_STR_BEFORE = '<div class="form_error">';    // バリデーション囲い文字（前）
    const VALID_STR_AFTER = '</div>';                       // バリデーション囲い文字（後）
    const VALID_ADD_NAME = 'valid_error';                   // バリデーションエラー時の追加フォーム名

    // ハイフン
    const STR_HYPHEN = '-';

    // 区切り文字
    const STR_DELIMITER_DISPLAY = '、';                     // 表示用
    const STR_DELIMITER_SYSTEM = ',';                       // システム用

    // 文字列生成用文字数
    const DEFAULT_CREATE_ID_NUM = 10;

    /**
     * var
     */
    private $dbTable;
    // スーパーオブジェクト割当用変数
    protected $CI;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        setlocale(LC_MONETARY, "ja_JP.UTF8"); // ロケール
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        /*
                $is_win = strpos(PHP_OS, "WIN") === 0;
                // Windowsの場合は Shift_JIS、Unix系は UTF-8で処理
                if ( $is_win ) {
                    setlocale(LC_ALL, "Japanese_Japan.932");
                }
                else {
                    setlocale(LC_ALL, "ja_JP.UTF-8");
                }
        */
    }


    /**
     * 配列をプルダウン用に形に成形してはき出す
     *
     * @param array $arrayData：対象プルダウン用配列
     * @param string $defaultWord：無選択時の文字列
     * @return array|null
     */
    public static function SelectboxDefalutForm(
        $arrayData = array(),
        $defaultWord = ""
    ) : ?array {
        // 初期値をセット
        $defaultArray[''] = ($defaultWord ? $defaultWord : self::DEFAULT_SELECT_FIRST_WORD);
        // 無選択時の文字列を配列に追加
        $returnVal = (count($arrayData) > 0 ? $defaultArray + $arrayData : $defaultArray);

        return $returnVal;
    }


    /**
     * サーバー設定の現年月日を返す
     *
     * @return string
     */
    public static function NowDate() : string
    {
        return date('Y-m-d');
    }


    /**
     * サーバー設定の現年月日時分秒を返す
     *
     * @return string
     */
    public static function NowDateTime() : string
    {
        return date('Y-m-d H:i:s');
    }


    /**
     * 数値を金額文字列にフォーマット
     *
     * @param string $num：対象数値文字列
     * @return string|null
     */
    public static function NumFormat(int $num = 0) : ?string
    {
        // 返値を初期化
        $returnVal = 0;
        // 数値の場合
        if (is_numeric($num)) {
            // 金額文字列に置換
            $returnVal = self::AddSlashes(number_format($num));
        }
        return $returnVal;
    }


    /**
     * 文字列をスラッシュでクォートする
     *
     * @param string|null $str：対象文字列
     * @return string|null
     */
    public static function AddSlashes(?string $str = '') : ?string
    {
        $str = addslashes($str);
        $str = preg_replace("/\\'/", "'", $str);

        $str = str_replace("\'", "'", $str);
        $str = str_replace("'", "''", $str);
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        $str = str_replace(";", "\\;", $str);

        return $str;
    }


    /**
     * 文字列が空の場合ヌルを返す
     *
     * @param string $data：対象文字列
     * @return string|null
     */
    public static function EmptyToNull($data = '') : ?string
    {
        if (($data == "" && $data !==  0) || !isset($data)) {
            $data = "NULL";
        }
        return self::AddSlashes($data);
    }


    /**
     * [validation - in_list]用の配列キーから変換した文字列を返す
     *
     * @param array $array：対象配列
     * @return string|null
     */
    public static function GetConvValidInList(array $array = array()) : ?string
    {
        return @implode(self::STR_DELIMITER_SYSTEM, array_keys($array));
    }


    /**
     * 対象リストをテーブル用構造に変換して返す
     *
     * @param array $arrayVal：対象配列
     * @param string $wrapCount：改行行数
     * @return array|null
     */
    public static function EditTableForm(
        $arrayVal = array(),
        $wrapCount = 0
    ) : ?array {
        $n = ceil(count($arrayVal) / $wrapCount) * $wrapCount;
        for ($i = 0; $i < $n; $i ++) {
            if ($i == 0) {
                $arrayVal[$i]['top'] = true;
            }
            if (($i + 1) % $wrapCount == 0) {
                $arrayVal[$i]['wrap'] = true;
                if ($i < $n - 1) {
                    $arrayVal[$i + 1]['top'] = true;
                }
            }
        }
        return $arrayVal;
    }


    /**
     * ベースクラス定数一覧を取得
     *
     * @return array|null
     */
    public static function GetBaseConstList(): ?array
    {
        // 返値を初期化
        $returnVal = array();
        // 定数取得用クラス宣言
        $targetClass = new ReflectionClass(__CLASS__);
        // 定数一覧を配列で取得
        $tempVal = $targetClass->getConstants();
        foreach ($tempVal as $key => $val) {
            // 配列キーを小文字に変換
            $returnVal[mb_strtolower($key)] = $val;
        }

        return $returnVal;
    }


    /**
     * 対象クラス定数一覧を取得
     *
     * @param string $className：対象クラス名
     * @return array|null
     */
    public static function GetConstList(string $className = ''): ?array
    {
        // 返値を初期化
        $returnVal = array();
        // 対象クラス名が未セットの場合、ベースクラス定数をセット
        if (! $className) {
            return self::GetBaseConstList();
        }
        // 定数取得用クラス宣言
        $targetClass = new ReflectionClass($className);
        // 定数一覧を配列で取得
        $tempVal = $targetClass->getConstants();
        foreach ($tempVal as $key => $val) {
            // 配列キーを小文字に変換
            $returnVal[mb_strtolower($key)] = $val;
        }

        return $returnVal;
    }


    /**
     * サイトホストを返す
     *
     * @return string|null
     */
    public static function SiteHost() : ?string
    {
        return self::SITE_DOMAIN;
    }


    /**
     * サイトメールアドレスを返す
     *
     * @return string|null
     */
    public static function SiteMail() : ?string
    {
        return "info@" . self::SiteHost();
    }


    /**
     * 管理者メールアドレスを返す
     *
     * @return string|null
     */
    public static function AdminMail() : ?string
    {
//        return "admin@" . self::SiteHost();
        return self::ADMIN_MAIL;
    }


    /**
     * 差出人用メールアドレスを返す
     *
     * @return string|null
     */
    public static function SiteMailFrom() : ?string
    {
        return self::SITE_TITLE_NAME . " <" . self::SiteMail() . ">";
    }


    /**
     * エラー時返信用メールアドレスを返す
     *
     * @return string
     */
    public static function SiteMailReply() : string
    {
        return "error@" . self::SiteHost();
    }


    /**
     * 対象データをコンソールログに表示 インスタンス版
     *
     * @param string|array $targetData：対象データ
     * @return void
     */
    public function ConsoleLogInst($targetData) : void
    {
        // JS/CSSメソッドの場合は処理を除外
        if (
            $this->CI->router->fetch_method() != Base_lib::JS_DIR &&
            $this->CI->router->fetch_method() != Base_lib::CSS_DIR
        ) {
            // 対象データをコンソールログに表示
            self::ConsoleLog($targetData);
        }
    }


    /**
     * 対象データをコンソールログに表示
     *
     * @param string|array $targetData：対象データ
     * @return void
     */
    public static function ConsoleLog($targetData)
    {
        if (
            ENVIRONMENT == 'development' &&
            $targetData
        ) {
            echo '<script>';
            echo 'console.log(' . json_encode($targetData) . ')';
            echo '</script>';
        }
    }


    /**
     * トークンを生成
     *
     * @return string
     */
    public function CreateTokenKey() : string
    {
        return bin2hex(random_bytes(32));
    }


    /**
     * DBテーブル情報（メンバー変数）をセット
     *
     * @param string $targetData：対象データ
     * @return void
     */
    public function SetDbTable(string $tableName = '') : void
    {
        $this->dbTable = $tableName;
    }


    /**
     * DBテーブル情報（メンバー変数）を取得
     *
     * @return string|null
     */
    public function GetDbTable() : string
    {
        return $this->dbTable;
    }


    /**
     * DBテーブル情報（メンバー変数）の確認
     *
     * @return bool
     */
    public function CheckDbTable() : bool
    {
        return ($this->dbTable ? true : false);
    }


    /**
     * カラム名（スネークケース）をキャメルケースに変換し取得
     *
     * @param string $targetName：対象名
     * @return string
     */
    public function GetCamelName(string $targetName = '') : string
    {
        return lcfirst(strtr(ucwords(strtr($targetName, ['_' => ' '])), [' ' => '']));
    }


    /**
     * 以下、マスタライブラリ継承用関数
     */



    /**
     * 名前一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetNameList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'name', $public);
    }


    /**
     * 内容一覧を取得
     *
     * @param bool $public：ステータスフラグ
     * @return array|null
     */
    public function GetContentsList(bool $public = false) : ?array
    {
        return $this->CI->db_lib->GetSelectValues($this->GetDbTable(), 'contents', $public);
    }


    /**
     * 名前を取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetName(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'name', $id, 'id', $public);
    }


    /**
     * 内容を取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetContents(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'contents', $id, 'id', $public);
    }


    /**
     * ユーザーIDをを取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetUserId(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'user_id', $id, 'id', $public);
    }


    /**
     * カテゴリーIDをを取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetCategoryId(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'category_id', $id, 'id', $public);
    }


    /**
     * 順番を取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetSortId(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'sort_id', $id, 'id', $public);
    }


    /**
     * 表示ステータスを取得
     *
     * @param string $id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetStatus(string $id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'status', $id, 'id', $public);
    }


    /**
     * 名前からIDを取得
     *
     * @param string $name
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromName(string $name, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $name, 'contents', $public);
    }


    /**
     * 内容からIDを取得
     *
     * @param string $contents
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromContents(string $contents, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $contents, 'contents', $public);
    }


    /**
     * ユーザーIDからIDを取得
     *
     * @param string $userId
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromUserId(string $userId, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $userId, 'user_id', $public);
    }


    /**
     * カテゴリーIDからIDを取得
     *
     * @param string $cateogryId
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromCategoryId(string $cateogryId, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $cateogryId, 'category_id', $public);
    }


    /**
     * 順番からIDを取得
     *
     * @param string $sort_id
     * @param boolean $public：ステータスフラグ
     * @return string|null
     */
    public function GetIdFromSortId(string $sort_id, bool $public = false) : ?string
    {
        return $this->CI->db_lib->GetValue($this->GetDbTable(), 'id', $sort_id, 'sort_id', $public);
    }


    /**
     * IDの登録有無
     *
     * @param string|null $id
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function IdExists(?string $id, bool $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $id, 'id', $public);
    }


    /**
     * 名前の登録有無
     *
     * @param string|null $name
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function NameExists(?string $name, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $name, 'name', $public);
    }


    /**
     * ユーザーIDの登録有無
     *
     * @param string $userId
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function UserIdExists($userId, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $userId, 'user_id', $public);
    }


    /**
     * カテゴリーIDの登録有無
     *
     * @param string $categoryId
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function CategoryIdExists($categoryId, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $categoryId, 'category_id', $public);
    }


    /**
     * 内容の登録有無
     *
     * @param string $contents
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function ContentsExists($contents, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $contents, 'contents', $public);
    }


    /**
     * 順番の登録有無
     *
     * @param string $sort_id
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function SortIdExists($sort_id, $public = false) : bool
    {
        return $this->CI->db_lib->ValueExists($this->GetDbTable(), $sort_id, 'sort_id', $public);
    }


    /**
     * 名前が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $name：対象名前
     * @param string $id：除外ID
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function NameSameExists($name, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists($this->GetDbTable(), $name, 'name', $id, 'id', $public);
    }


    /**
     * 内容が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $contents：対象内容
     * @param string $id：除外ID
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function ContentsSameExists($contents, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists($this->GetDbTable(), $contents, 'contents', $id, 'id', $public);
    }


    /**
     * 順番が対象ID以外に同じ値が存在するかどうか
     *
     * @param string $sort_id：対象順番
     * @param string $id：除外ID
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function SortIdSameExists($sort_id, $id = '', $public = false) : bool
    {
        return $this->CI->db_lib->SameExists($this->GetDbTable(), $sort_id, 'sort_id', $id, 'id', $public);
    }


    /**
     * IDを生成
     *
     * @param string $strNum：文字数
     * @param boolean $public：ステータスフラグ
     * @return boolean
     */
    public function CreateId($strNum, $public = false)
    {
        // 未登録のランダム文字列を生成
        return $this->CI->db_lib->CreateStr($this->GetDbTable(), $strNum, $public);
    }


    /**
     * DB登録処理
     *
     * @param array|null $registData：登録内容（連想配列[key : 対象カラム, value : 値]）
     * @param string|null $id：登録対象ID
     * @return string|null
     */
    public function Regist(?array $registData = array(), ?string $id = '') : ?string
    {
        // 返り値をセット
        $returnVal = false;
        // 配列形式の確認
        if (is_array($registData)) {
            // ユーザーIDが登録されているか
            if ($this->IdExists($id)) {
                // 更新処理
                $returnVal = $this->CI->db_lib->Update($this->GetDbTable(), $registData, $id);
            } else {
                // IDが未セットの場合、IDを生成
                if (
                    ! isset($registData['id']) ||
                    $registData['id'] == ''
                ) {
                    // 自動採番以外、かつINT型以外
                    if (
                        ! $this->CI->db_lib->CheckAutoIncrement($this->GetDbTable()) &&
                        ! $this->CI->db_lib->CheckTypeInt($this->GetDbTable())
                    ) {
                        // ID文字列数の設定が存在
                        if (defined(self::CREATE_ID_NUM)) {
                            $strNum = self::CREATE_ID_NUM;
                        }
                        // ID文字列数の設定が
                        else {
                            $strNum = self::DEFAULT_CREATE_ID_NUM;
                        }
                        $registData['id'] = $this->CreateId($strNum);
                    }
                    // 自動採番
                    else {
                        $registData['id'] = ($this->CI->db_lib->GetValueMax($this->GetDbTable(), 'id') + 1);
                    }
                }
                // 新規作成
                $returnVal = $this->CI->db_lib->Insert($this->GetDbTable(), $registData);
            }
            // 登録成功の場合、IDを返す
            if (
                $returnVal &&
                isset($registData['id'])
            ) {
                $returnVal = $registData['id'];
            }
        }
        return $returnVal;
    }


    /**
     * DB削除処理
     *
     * @param string $id：対象ID
     * @param bool $logicFlg：論理削除フラグ
     * @return boolean|null
     */
    public function Delete(string $id, bool $logicFlg = true) : ?bool
    {
        // 返り値をセット
        $returnVal = false;
        // 対象IDが登録されているか
        if ($this->IdExists($id, true)) {
            // 削除処理
            $returnVal = $this->CI->db_lib->Delete($this->GetDbTable(), $logicFlg, $id);
        }
        return $returnVal;
    }


    /**
     * DBソート更新処理
     *
     * @param string $id：対象ID
     * @param string $targetSort：対象ソートID
     * @param string|null $column：対象カラム
     * @return void
     */
    public function UpdateSort(
        string $id,
        string $targetSort,
        ?string $column = 'sort_id'
    ) : void {
        // 現在のソートを取得
        $sortNow = $this->CI->db_lib->GetValue($this->GetDbTable(), $column, $id);
        // トランザクション開始
        $this->CI->db->trans_start();
        // WHERE情報をセット
        $this->CI->db->where($column . ' ' . ($targetSort > $sortNow ? '<=' : '>='), $targetSort);
        $this->CI->db->where($column . ' ' . ($targetSort > $sortNow ? '>=' : '<='), $sortNow);
        // 更新
        $this->CI->db->set($column, $column . ($targetSort > $sortNow ? '-' : '+') . '1', false);
        // 更新処理
        $returnVal = $this->CI->db->update($this->GetDbTable());

        // WHERE情報をセット
        $this->CI->db->where('id', $id);
        // 更新
        $this->CI->db->set($column, $targetSort, false);
        // 更新処理
        $returnVal = $this->CI->db->update($this->GetDbTable());
        // トランザクション終了
        $this->CI->db->trans_complete();
    }


    /**
     * 日付情報より[date('YYYY-mm-dd')]より曜日名(日本語)を取得
     *
     * @param string $date：対象日（YYYY-mm-dd）
     * @param bool $shortFlg：省略フラグ
     * @return string
     */
    public function WeekDayJp(
        string $date,
        bool $shortFlg = false
    ) : string {
        // 返り値をセット
        $returnVal = '';
        // 曜日の配列一覧
        $weekList[] = array('normal' => '日曜日', 'short' => '日' );
        $weekList[] = array('normal' => '月曜日', 'short' => '月' );
        $weekList[] = array('normal' => '火曜日', 'short' => '火' );
        $weekList[] = array('normal' => '水曜日', 'short' => '水' );
        $weekList[] = array('normal' => '木曜日', 'short' => '木' );
        $weekList[] = array('normal' => '金曜日', 'short' => '金' );
        $weekList[] = array('normal' => '土曜日', 'short' => '土' );
        // 曜日番号をセット
        $weekDay = date('w', strtotime($date));
        // 曜日番号が取得出来ている場合
        if ($weekDay !== null) {
            // 曜日名(日本語)をセット
            $returnVal = $weekList[$weekDay][(!$shortFlg ? 'normal' : 'short')];
        }
        return $returnVal;
    }
}
