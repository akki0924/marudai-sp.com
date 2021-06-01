<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能 : ベース用マスタ処理プログラム
    ■概　要 : 初期設定定数、コモン関数などのプログラム
    ■更新日 : 2020/01/24
    ■担　当 : crew.miwa

    ■更新履歴：
        2015/06/23 : 作成開始
        2020/01/24 : マスタディレクトリを追加
    */

class Base_lib
{
    //=======================================
    // 定数定義

    // サイト情報
    const SITE_TITLE_NAME = "サンプルサイト";
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
    // ハイフン
    const STR_HYPHEN = '-';
    // 区切り文字
    const STR_DELIMITER_DISPLAY = '、';                     // 表示用
    const STR_DELIMITER_SYSTEM = ',';                       // システム用

    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        setlocale(LC_MONETARY, "ja_JP.UTF8"); // ロケール
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
    /*====================================================================
        関数名 : SelectboxDefalutForm
        概　要 : 配列をプルダウン用に形に成形してはき出す
        引　数 : $array : 対象プルダウン用配列
                 $defaultWord : 無選択時の文字列
    */
    public static function SelectboxDefalutForm($array = "", $defaultWord = "")
    {
        $default_array[''] = ($defaultWord ? $defaultWord : self::DEFAULT_SELECT_FIRST_WORD);
        $returnVal = (is_array($array) ? $default_array + $array : $default_array);

        return $returnVal;
    }
    /*====================================================================
        関数名 : NowDate
        概　要 : DB登録用の現在日をセット
    */
    public static function NowDate()
    {
        return date('Y-m-d');
    }
    /*====================================================================
        関数名 : NowDateTime
        概　要 : DB登録用の現在日時をセット
    */
    public static function NowDateTime()
    {
        return date('Y-m-d H:i:s');
    }
    /*====================================================================
        関数名 : NumFormat
        概　要 : 数値を金額文字列にフォーマット
        引　数 : $num : 対象数字
    */
    public static function NumFormat($num)
    {
        $returnVal = '';
        // 数値の場合
        if (is_numeric($num)) {
            $returnVal = self::AddSlashes(number_format($num));
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : AddSlashes
        概　要 : 文字列をスラッシュでクォートする
        引　数 : $str : 対象文字列
    */
    public static function AddSlashes($str)
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
    /*====================================================================
        関数名 : EmptyToNull
        概　要 : 文字列が空の場合ヌルを返す
        引　数 : $data : 対象文字列
    */
    public static function EmptyToNull($data)
    {
        if (($data == "" && $data !==  0) || !isset($data)) {
            $data = "NULL";
        }
        return self::AddSlashes($data);
    }
    /*====================================================================
        関数名 : GetConvValidInList
        概　要 : validation - in_list用の配列キーから変換した文字列を返す
        引　数 : $array : 対象配列
    */
    public static function GetConvValidInList($array)
    {
        return @implode(array_keys($array), self::STR_DELIMITER_SYSTEM);
    }
    /*====================================================================
        関数名： EditTableForm
        概　要： 対象リストをテーブル用構造に変換して返す
        引　数 : $arrayVal : 対象配列
                $wrapCount : 改行行数
    */
    public static function EditTableForm($arrayVal, $wrapCount)
    {
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
    /*====================================================================
        関数名： GetBaseConstList
        概　要： ベースクラス定数一覧を取得
        戻り値： クラス定数一覧
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
    /*====================================================================
        関数名： GetConstList
        概　要： 対象クラス定数一覧を取得
        引　数 : $className : 対象クラス名
        戻り値： クラス定数一覧
    */
    public static function GetConstList(string $className = ''): array
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
    /*====================================================================
        関数名： SiteHost
        概　要： サイトホストを返す
    */
    public static function SiteHost()
    {
        return $_SERVER["HTTP_HOST"];
    }
    /*====================================================================
        関数名： SiteMail
        概　要： サイトメールアドレスを返す
    */
    public static function SiteMail()
    {
        return "info@" . self::SiteHost();
    }
    /*====================================================================
        関数名： AdminMail
        概　要： 管理者メールアドレスを返す
    */
    public static function AdminMail()
    {
//        return "admin@" . self::SiteHost();
        return "miwa@ccrw.co.jp";
    }
    /*====================================================================
        関数名： SiteMailFrom
        概　要： 差出人用メールアドレスを返す
    */
    public static function SiteMailFrom()
    {
        return self::SITE_TITLE_NAME . " <" . self::SiteMail() . ">";
    }
    /*====================================================================
        関数名： SiteMailReply
        概　要： エラー時返信用メールアドレスを返す
    */
    public static function SiteMailReply()
    {
        return "error@" . self::SiteHost();
    }
    /*====================================================================
        関数名： ConsoleLog
        概　要： 対象データをコンソールログに表示
        引　数 : $targetData : 対象データ
    */
    public static function ConsoleLog($targetData = "")
    {
        if (ENVIRONMENT == 'development') {
            echo '<script>';
            echo 'console.log('. json_encode($targetData) .')';
            echo '</script>';
        }
    }
    /*====================================================================
        関数名： CreateTokenKey
        概　要： トークンを生成
    */
    public function CreateTokenKey()
    {
        return bin2hex(random_bytes(32));
    }
}
