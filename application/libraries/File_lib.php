<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能： ファイル用サポート処理ライブラリー
    ■概　要： ファイル用登録関数群
    ■更新日： 2018/10/24
    ■担　当： crew.miwa

    ■更新履歴：
     2018/10/24: 作成開始

    */

class File_lib
{
    const SEPARATOR_STR = '\\';

    /*====================================================================
        関数名： GetDetailList
        概　要： ファイル詳細一覧取得処理
        引　数： $dir：ディレクトリ
    */
    public function GetDetailList($dir)
    {
        // 返値の初期値をセット
        $returnList = array();

        // ファイル名一覧
        $nameList = $this->GetNameList($dir);

        for ($i = 0, $n = count($nameList); $i < $n; $i ++) {
            // ファイル名
            $returnList[$i]['name'] = $nameList[$i];
            // 拡張子
            $returnList[$i]['ext'] = $this->GetExt($nameList[$i]);
            // パス
            $returnList[$i]['path'] = $this->GetPath($dir, $nameList[$i]);
            // サイズ
            $returnList[$i]['size'] = $this->GetSize($returnList[$i]['path']);
        }

        return $returnList;
    }
    /*====================================================================
        関数名： GetNameList
        概　要： ファイル一覧取得処理
        引　数： $dir：ディレクトリ
    */
    public function GetNameList($dir)
    {
        // 返値の初期値をセット
        $returnList = array();

        // ディレクトリが存在する場合
        if ($dir != '' && is_dir($dir)) {
            // 対象のディレクトリー内一覧を取得
            $file_list = scandir($dir);

            foreach ($file_list as $file) {
                if (
                    isset($file) &&
                    ! is_dir($dir . self::SEPARATOR_STR . $file) &&
                    $file != '.' &&
                    $file != '..'
                ) {
                    // ファイル一覧のみ抽出
                    $returnList[] = $file;
                }
            }
        }

        return $returnList;
    }
    /*====================================================================
        関数名： GetDirList
        概　要： ディレクトリ一覧取得処理
        引　数： $dir：ディレクトリ
    */
    public function GetDirList($dir)
    {
        // 返値の初期値をセット
        $returnList = array();

        // ディレクトリが存在する場合
        if ($dir != '' && is_dir($dir)) {
            // 対象のディレクトリー内一覧を取得
            $file_list = scandir($dir);

            foreach ($file_list as $file) {
                if (
                    isset($file) &&
                    is_dir($dir . self::SEPARATOR_STR . $file) &&
                    $file != '.' &&
                    $file != '..'
                ) {
                    // ディレクトリ一覧のみ抽出
                    $returnList[] = $file;
                }
            }
        }

        return $returnList;
    }
    /*====================================================================
        関数名： GetPathList
        概　要： ファイルパス一覧取得処理
        引　数： $dir：ディレクトリ
    */
    public function GetPathList($dir)
    {
        // 返値の初期値をセット
        $returnList = array();

        // ファイル名一覧を取得
        $file_list = $this->GetNameList($dir);
        // ファイル一覧が存在する場合
        if (! empty($file_list)) {
            foreach ($file_list as $file) {
                // ファイル一覧にディレクトリ情報を追加してセット
                $returnList[] = $dir . self::SEPARATOR_STR . $file;
            }
        }

        return $returnList;
    }
    /*====================================================================
        関数名： GetPath
        概　要： ファイルパス取得処理
        引　数： $dir：ディレクトリ
                $file: ファイル
    */
    public function GetPath($dir, $file)
    {
        // 返値の初期値をセット
        $returnVal = '';

        // 接続文字
        $join_str = '';
        // ファイル名が存在する場合
        if (mb_substr($dir, -1) !== self::SEPARATOR_STR) {
            $join_str = self::SEPARATOR_STR;
        }
        // ファイル一覧にディレクトリ情報を追加してセット
        $returnVal = $dir . $join_str . $file;

        return $returnVal;
    }
    /*====================================================================
        関数名： GetExt
        概　要： ファイル拡張子取得処理
        引　数： $file：ファイル名
    */
    public function GetExt($file)
    {
        // 返値の初期値をセット
        $returnVal = '';

        if (mb_strpos($file, '.') !== false) {
            $returnVal = substr($file, strrpos($file, '.') + 1);
        }

        return $returnVal;
    }
    /*====================================================================
        関数名： GetSize
        概　要： ファイルサイズ取得処理
        引　数： $path：ファイルパス
    */
    public function GetSize($path)
    {
        // 返値の初期値をセット
        $returnVal = '';
        // ファイルパスが存在する場合
        if (file_exists($path)) {
            $returnVal = filesize($path);
        }

        return $returnVal;
    }
    /*====================================================================
        関数名： ByteFormat
        概　要： ファイルサイズ取得処理
        引　数： $size：バイトサイズ
                $dec：小数点以下の桁数
                $separate：カンマ区切り
    */
    public function ByteFormat($size, $dec = -1, $separate = false)
    {
        // 単位配列
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
        $digits = ($size == 0) ? 0 : floor(log($size, 1024));

        $over = false;
        $max_digit = count($units) -1 ;

        if ($digits == 0) {
            $num = $size;
        } elseif (! isset($units[$digits])) {
            $num = $size / (pow(1024, $max_digit));
            $over = true;
        } else {
            $num = $size / (pow(1024, $digits));
        }

        if ($dec > -1 && $digits > 0) {
            $num = sprintf("%.{$dec}f", $num);
        }
        if ($separate && $digits > 0) {
            $num = number_format($num, $dec);
        }

        return ($over) ? $num . $units[$max_digit] : $num . $units[$digits];
    }
    /*====================================================================
        関数名： GetExtToMine
        概　要： 拡張子からMINEタイプを取得
        引　数： $ext：拡張子
    */
    public function GetExtToMine($ext)
    {
        // 返り値を初期化
        $returnVal = '';

        // 大文字を小文字に変換
        $ext = mb_strtolower($ext);

        // JPEG
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $returnVal = 'image/jpeg';
        }
        // PNG
        elseif ($ext == 'png') {
            $returnVal = 'image/png';
        }
        // GIF
        elseif ($ext == 'gif') {
            $returnVal = 'image/gif';
        }
        // TXT
        elseif ($ext == 'txt') {
            $returnVal = 'text/plain';
        }
        // PDF
        elseif ($ext == 'pdf') {
            $returnVal = 'application/pdf';
        }
        // DOC
        elseif ($ext == 'doc') {
            $returnVal = 'application/msword';
        }
        // DOCX
        elseif ($ext == 'docx') {
            $returnVal = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }
        // XLS
        elseif ($ext == 'xls') {
            $returnVal = 'application/vnd.ms-excel';
        }
        // XLSX
        elseif ($ext == 'xlsx') {
            $returnVal = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }
        // PPT
        elseif ($ext == 'ppt') {
            $returnVal = 'application/vnd.ms-powerpoint';
        }
        // PPTX
        elseif ($ext == 'pptx') {
            $returnVal = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        }
        // CSV
        elseif ($ext == 'csv') {
            $returnVal = 'text/csv';
        }

        return $returnVal;
    }
    /*====================================================================
        関数名： FileLibraryExists
        概　要： ライブラリー内に対象ファイルが存在するか確認
        引　数： $filePath：ファイルパス
    */
    public function FileExists($filePath = '')
    {
        // 返値の初期値をセット
        $returnVal = false;
        // ファイルパスがセット
        if ($filePath) {
            // 対象ディレクトリをセット
            $targetDir = APPPATH . Base_lib::LIBRARY_DIR . DIRECTORY_SEPARATOR;
            // ファイルの存在確認
            $returnVal = file_exists($targetDir . DIRECTORY_SEPARATOR . $filePath);
        }
        return $returnVal;
    }
}
