<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * JSONサポート処理ライブラリー
 *
 * JSONサポート用関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     2021/04/26
 */
class Json_lib
{
    /**
     * const
     */
    // 構文エラー時のメッセージ
    const MSG_SYNTAX_ERROR = 'Syntax error';
    // ダブルコーテーション
    const STR_DOUBLE_QUOTE = '"';
    // 各括弧
    const STR_OPEN_BRAKETS = '[';
    const STR_CLOSE_BRAKETS = ']';
    const STR_OPEN_BRACES = '{';
    const STR_CLOSE_BRACES = '}';
    // カンマ
    const STR_COMMA = ',';

    /**
     * JSONエンコード処理（お勧めオプション設定）
     *
     * @param array $targetData：対象配列
     * @return string|null
     */
    public function Encode(array $targetData) : ?string
    {
        // エンコード処理
        $returnVal = json_encode(
            $targetVal,
            JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
        );

        return $returnVal;
    }


    /**
     * JSONデコード処理（お勧めオプション設定）
     *
     * @param string $targetData：対象JSONデータ
     * @return array|null
     */
    public function Decode(string $targetVal) : ?array
    {
        // デコード処理
        $returnVal = json_decode($targetVal, true);
        // エラー時にログ出力
        $this->ErrorLog($targetVal);

        return $returnVal;
    }


    /**
     * JSONデコード処理中のエラーをログに表示
     *
     * @param string $targetData：対象JSONデータ
     * @return void
     */
    public function ErrorLog(string $targetVal) : void
    {
        // 直前JSONデコード処理がエラー時
        if (json_last_error() !== JSON_ERROR_NONE) {
            // エラーメッセージをセット
            $errMsg = json_last_error_msg();
            // 構文エラー
            if ($errMsg == self::MSG_SYNTAX_ERROR) {
                // 全行数を取得
                $dataLow = explode("\n", $targetVal);
                for ($i = 0, $rowNum = 1, $n = count($dataLow); $i < $n; $i ++, $rowNum ++) {
                    // 行末がダブルコーテーション
                    if (substr($dataLow[$i], -1) == self::STR_DOUBLE_QUOTE) {
                        if ($i < $n) {
                            // 次行の最終文字
                            $nextLastStr = substr($dataLow[($i + 1)], -1);
                            $nextLastStrs = substr($dataLow[($i + 1)], -2);
                            if (
                                $nextLastStr != self::STR_CLOSE_BRAKETS &&
                                $nextLastStr != self::STR_CLOSE_BRACES &&
                                $nextLastStrs != (self::STR_CLOSE_BRAKETS . self::STR_COMMA) &&
                                $nextLastStrs != (self::STR_CLOSE_BRACES . self::STR_COMMA)
                            ) {
                                // 最初の構文エラー行
                                if (!isset($errRowFlg)) {
                                    $errMsg .= "エラー行：";
                                }
                                // 以降の構文エラー行
                                else {
                                    $errMsg .= self::STR_COMMA . " ";
                                }
                                $errMsg .= $rowNum . "行目";
                                // エラー行情報フラグをセット
                                $errRowFlg = true;
                            }
                        }
                    }
                }
            }
            // エラーメッセージをログ出力
            Base_lib::ConsoleLog('JSONエラー：' . $errMsg);
        }
    }
}
