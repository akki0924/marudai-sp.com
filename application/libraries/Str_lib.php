<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 文字列用サポート処理ライブラリー
 *
 * 文字列登録関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     2021/03/10
 */
class Str_lib
{
    /**
     * キーワード文字列からスペース毎に分割した値を取得
     *
     * @param string $keyword
     * @return array
     */
    public function GetKeywordConvertVal(string $keyword) : array
    {
        // ホワイトスペース削除
        $keyword = trim($keyword);
        // スペースを統一
        $keyword = str_replace('　', ' ', $keyword);

        // スペースが存在する場合
        if (strpos($keyword, ' ') !== false) {
            // スペース毎に分割
            $returnVal = explode(' ', $keyword);
        } else {
            $returnVal[] = $keyword;
        }

        return $returnVal;
    }
    /**
     * 対象文字列の文字コードをUTF8に変換して返す
     *
     * @param string $str
     * @return string
     */
    public function GetConvertUtf8(string $str = '') : string
    {
        // 返値を初期化
        $returnVal = "";
        if ($str) {
            $bom = pack('H*','EFBBBF');
            $str = preg_replace("/^$bom/", '', $str);
            $returnVal = mb_convert_encoding($str, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN,SJIS");
        }
        return $returnVal;
    }
}
