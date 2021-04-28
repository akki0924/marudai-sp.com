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
    // コロン
    const STR_COLON = ':';
    // 半角空白
    const STR_BLANK = ' ';

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
    /**
     * JSONデータ内のダブルコーテーションをクォート処理
     *
     * @param string $targetVal：対象JSON文字列
     * @return string：変換したJSON文字列
     */
    public function EscapeDoubleQuote(string $targetVal) : string
    {
        // 全行数を配列で取得
        $dataLow = explode("\n", $targetVal);
        // 各行をループ
        for ($i = 0, $rowNum = 1, $n = count($dataLow); $i < $n; $i ++, $rowNum ++) {
            // コロンの最初の位置を取得
            $posColon = strpos($dataLow[$i], self::STR_COLON);
            // コロンが存在
            if ($posColon > 0) {
                // コロンの次の文字がコロン以外
                if (substr($dataLow[$i], ($posColon + 1), 1) != self::STR_COLON) {
                    $dataLine[0] = substr($dataLow[$i], 0, $posColon);
                    $dataLine[1] = substr($dataLow[$i], ($posColon + 1));
                }
                // コロンの次の文字もコロン
                else {
                    $dataLine[0] = '';
                    $dataLine[1] = $dataLow[$i];
                }
            }
            // コロンが未存在
            else {
                $dataLine[0] = '';
                $dataLine[1] = $dataLow[$i];
            }

            // ダブルコーテーションが２つ以上
            if (substr_count($dataLine[1], self::STR_DOUBLE_QUOTE) > 2) {
                // ダブルコーテーションの最初の位置
                $firstPos = strpos($dataLine[1], self::STR_DOUBLE_QUOTE);
                // ダブルコーテーションの最後の位置
                $lastPos = strrpos($dataLine[1], self::STR_DOUBLE_QUOTE);
                // ダブルコーテーションの最後の位置の前後の文字列を取得
                $editLine1 = substr($dataLow[$i], 0, ($lastPos));
                $editLine2 = substr($dataLow[$i], ($lastPos + 1));
                // ダブルコーテーションの最後の文字以外を再セット
                $dataLine[1] = ($editLine1 ? $editLine1 : '') . ($editLine2 ? $editLine2 : '');
                // ダブルコーテーションの最初の位置の前後の文字列を取得
                $editLine1 = substr($dataLine[1], 0, ($firstPos));
                $editLine2 = substr($dataLine[1], ($firstPos + 1));
                // ダブルコーテーションの最初の文字以外を再セット
                $dataLine[1] = ($editLine1 ? $editLine1 : '') . ($editLine2 ? $editLine2 : '');
                // 残りのダブルコーテーションをクォート処理
                $dataLine[1] = str_replace(self::STR_DOUBLE_QUOTE, '\\' . self::STR_DOUBLE_QUOTE, $dataLine[1]);
                // 最初と最後の文字列を取得
                $firstStr = substr($dataLine[1], 0, 1);
                $lastStr = substr($dataLine[1], -1);
                // 最後の文字列がカンマ
                if ($lastStr == self::STR_COMMA) {
                    $lastPos = strrpos($dataLine[1], self::STR_COMMA);
                    $dataLine[1] = substr($dataLine[1], 0, $lastPos);
                    $dataLine[1] .= self::STR_DOUBLE_QUOTE . self::STR_COMMA;
                }
                // 最後の文字列がカンマ以外
                else {
                    $dataLine[1] .= self::STR_DOUBLE_QUOTE;
                }
                // 最初の文字列が空白
                if ($firstStr == self::STR_BLANK) {
                    $targetPos = (strspn($dataLine[1], self::STR_BLANK) - 1);
                    $editLine1 = substr($dataLine[1], 0, $targetPos);
                    $editLine2 = substr($dataLine[1], ($targetPos + 1));
                    $dataLine[1] = $editLine1 . self::STR_DOUBLE_QUOTE . $editLine2;
                }
                // 最初の文字列が空白以外
                else {
                    $dataLine[1] = self::STR_DOUBLE_QUOTE . $dataLine[1];
                }
            }
            // キー、値が共にセットの場合
            if (
                $dataLine[0] != '' &&
                $dataLine[1] != ''
            ) {
                // キーと値の間にコロンを追加
                $dataLow[$i] = $dataLine[0] . self::STR_COLON . $dataLine[1];
            }
            // キーか値が未セットの場合
            else {
                // 値をそのままセット
                $dataLow[$i] = $dataLine[0] . $dataLine[1];
            }
        }
        // 各行の配列を改行で連結して返す
        return implode("\n", $dataLow);
    }
}
