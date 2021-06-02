<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * ベース用マスタ処理プログラム
 *
 * 初期設定定数、及び継承元ライブラリ
 *
 * @author akki.m
 * @version 1.1.0
 * @since 1.0.0     2015/06/23  作成開始
 * @since 1.0.6     2020/01/24  各ディレクトリ名のメンバー定数追加
 * @since 1.1.0     2021/06/02  各マスタライブラリ継承用の関数、メンバー変数の追加
 */
class Base_lib
{
    /**
     * const
     */
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


    /**
     * コンストラクタ
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
     * @param string $str：対象文字列
     * @return string|null
     */
    public static function AddSlashes(string $str = '') : ?string
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
        return @implode(array_keys($array), self::STR_DELIMITER_SYSTEM);
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
        return $_SERVER["HTTP_HOST"];
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
        return "miwa@ccrw.co.jp";
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
     * 対象データをコンソールログに表示
     *
     * @param string|array $targetData：対象データ
     * @return void
     */
    public static function ConsoleLog($targetData = "") : void
    {
        if (ENVIRONMENT == 'development') {
            echo '<script>';
            echo 'console.log('. json_encode($targetData) .')';
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
}
