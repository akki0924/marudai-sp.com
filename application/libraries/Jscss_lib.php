<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * JavaScriptおよびCSS処理用ライブラリー
 *
 * JavaScriptおよびCSSのPHP経由で出力に関連する関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     2021/02/27  新規作成
 */
class Jscss_lib extends Base_lib
{
    /**
     * const
     */
    // 各メッセージ
    const MSG_DIALOG_COMP = '情報の更新が完了しました。';
    const MSG_DIALOG_ERR = '情報の更新に失敗しました。';
    const MSG_DIALOG_CANCEL = 'キャンセルしました。';
    const MSG_DIALOG_NG = '必要な情報がセットされていません。';
    // 各セレクター
    const STR_SEL_HEAD = 'sel_';
    const SEL_BTN_ADD = '#add_btn';
    const SEL_BTN_LIST_EDIT = '.list_edit_btn';
    const SEL_BTN_LIST_DEL = '.list_del_btn';
    const SEL_LIST_LINES = '#list_lines';
    const SEL_LOADER = '#loading_overlay';
    const SEL_LOADER_CV = '.cv-spinner';
    const SEL_LOADER_SPINNER = '.spinner';
    // ローディング
    const TIME_LOADING_SPEED = 300;
    const TIME_LOADING_TIMEOUT = 500;
    // AJAX後画面への反映フラグ
    const KEY_AJAX_REACTION = 'reaction';
    const KEY_AJAX_REACTION_FLG = self::KEY_AJAX_REACTION . '_flg';
    // スーパーオブジェクト割当用変数
    protected $CI;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }

    /**
     * JS形式にして書出し処理
     *
     * @param string|null $strData
     * @return void
     */
    public function CreateJs(?string $strData = '')
    {
        //ヘッダー出力
        header('Content-Type: application/x-javascript; charset=utf-8');
        // JSテキスト出力
        echo $strData;
    }
    /**
     * 対象配列をJSONエンコードした文字列として返す。
     * オプション：マルチバイト文字列をそのままの形式で扱う
     *            スラッシュをエスケープしない
     *            書式の整形
     *
     * @param array $listData
     * @return string
     */
    public function GetJson(array $listData = array()) : ?string
    {
        // JSONエンコードして返す
        // 、スラッシュをエスケープせず、書式をスペースで整える
        return json_encode(
            $listData,
            JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT
        );
    }
    /**
     * CSS形式にして書出し処理
     *
     * @param string|null $strData
     * @return void
     */
    public function CreateCss(?string $strData = '')
    {
        //ヘッダー出力
        header('Content-type: text/css');
        // CSSテキスト出力
        echo $strData;
    }
    /**
     * クラス定数にセレクター要素名を追加して取得
     *
     * @return array
     */
    public function GetConstListAddSelName() : array
    {
        // 返値を初期化
        $returnVal = array();
        // クラス定数をセット
        $returnVal = $this->CI->jscss_lib->GetConstList(__CLASS__);

        foreach ($returnVal as $key => $value) {
            if (strpos($key, self::STR_SEL_HEAD) === 0) {
                // クラス定数の存在確認
                if (!isset($returnVal[$key . '_name'])) {
                    $returnVal[$key . '_name'] = $this->GetSelName($value);
                }
            }
        }
        return $returnVal;
    }
    /**
     * セレクター文字列から要素名を取得
     *
     * @param string|null $str
     * @return string
     */
    public function GetSelName(?string $str) : string
    {
        // 返値を初期化
        $returnVal = $str;
        // 文字列が2文字以上の場合
        if (
            $str &&
            strlen($str) > 1
        ) {
            $returnVal = substr($str, 1);
        }
        return $returnVal;
    }
}
