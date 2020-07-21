<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
    function GetDetailList ( $dir )
    {
        // 返値の初期値をセット
        $returnList = array ();
        
        // ファイル名一覧
        $nameList = $this->GetNameList ( $dir );
        
        for ( $i = 0, $n = count ( $nameList ); $i < $n; $i ++ )
        {
            // ファイル名
            $returnList[$i]['name'] = $nameList[$i];
            // 拡張子
            $returnList[$i]['ext'] = $this->GetExt ( $nameList[$i] );
            // パス
            $returnList[$i]['path'] = $this->GetPath ( $dir, $nameList[$i] );
            // サイズ
            $returnList[$i]['size'] = $this->GetSize ( $returnList[$i]['path'] );
        }
        
        return $returnList;
    }
    /*====================================================================
        関数名： GetNameList
        概　要： ファイル一覧取得処理
        引　数： $dir：ディレクトリ
    */
    function GetNameList ( $dir )
    {
        // 返値の初期値をセット
        $returnList = array ();
        
        // ディレクトリが存在する場合
        if ( $dir != '' && is_dir ( $dir ) )
        {
            // 対象のディレクトリー内一覧を取得
            $file_list = scandir ( $dir );
            
            foreach ( $file_list as $file )
            {
                if (
                    isset ( $file ) &&
                    ! is_dir ( $dir . self::SEPARATOR_STR . $file ) &&
                    $file != '.' &&
                    $file != '..'
                )
                {
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
    function GetDirList ( $dir )
    {
        // 返値の初期値をセット
        $returnList = array ();
        
        // ディレクトリが存在する場合
        if ( $dir != '' && is_dir ( $dir ) )
        {
            // 対象のディレクトリー内一覧を取得
            $file_list = scandir ( $dir );
            
            foreach ( $file_list as $file )
            {
                if (
                    isset ( $file ) &&
                    is_dir ( $dir . self::SEPARATOR_STR . $file ) &&
                    $file != '.' &&
                    $file != '..'
                )
                {
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
    function GetPathList ( $dir )
    {
        // 返値の初期値をセット
        $returnList = array ();
        
        // ファイル名一覧を取得
        $file_list = $this->GetNameList ( $dir );
        // ファイル一覧が存在する場合
        if ( ! empty ($file_list) )
        {
            foreach ( $file_list as $file )
            {
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
    function GetPath ( $dir, $file )
    {
        // 返値の初期値をセット
        $returnVal = '';
        
        // 接続文字
        $join_str = '';
        // ファイル名が存在する場合
        if ( mb_substr ( $dir, -1 ) !== self::SEPARATOR_STR )
        {
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
    function GetExt ( $file )
    {
        // 返値の初期値をセット
        $returnVal = '';
        
        if ( mb_strpos ( $file, '.' ) !== false )
        {
            $returnVal = substr ($file, strrpos ( $file, '.' ) + 1 );
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： GetSize
        概　要： ファイルサイズ取得処理
        引　数： $path：ファイルパス
    */
    function GetSize ( $path )
    {
        // 返値の初期値をセット
        $returnVal = '';
        // ファイルパスが存在する場合
        if ( file_exists ( $path ) )
        {
            $returnVal = filesize ( $path );
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
    function ByteFormat ( $size, $dec = -1, $separate = false )
    {
        // 単位配列
        $units = array ( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
        $digits = ( $size == 0 ) ? 0 : floor ( log ( $size, 1024 ) );
        
        $over = false;
        $max_digit = count ( $units ) -1 ;
        
        if( $digits == 0 )
        {
            $num = $size;
        }
        else if ( ! isset ( $units[$digits] ) )
        {
            $num = $size / ( pow ( 1024, $max_digit ) );
            $over = true;
        }
        else
        {
            $num = $size / ( pow ( 1024, $digits ) );
        }
        
        if ( $dec > -1 && $digits > 0 ) $num = sprintf ( "%.{$dec}f", $num );
        if ( $separate && $digits > 0 ) $num = number_format ($num, $dec );
        
        return ( $over ) ? $num . $units[$max_digit] : $num . $units[$digits];
    }
    /*====================================================================
        関数名： GetExtToMine
        概　要： 拡張子からMINEタイプを取得
        引　数： $ext：拡張子
    */
    function GetExtToMine ( $ext )
    {
        // 返り値を初期化
        $returnVal = '';
        
        // 大文字を小文字に変換
        $ext = mb_strtolower ( $ext );
        
        // JPEG
        if ( $ext == 'jpg' || $ext == 'jpeg' ) {
            $returnVal = 'image/jpeg';
        }
        // PNG
        else if ( $ext == 'png' ) {
            $returnVal = 'image/png';
        }
        // GIF
        else if ( $ext == 'gif' ) {
            $returnVal = 'image/gif';
        }
        // TXT
        else if ( $ext == 'txt' ) {
            $returnVal = 'text/plain';
        }
        // PDF
        else if ( $ext == 'pdf' ) {
            $returnVal = 'application/pdf';
        }
        // DOC
        else if ( $ext == 'doc' ) {
            $returnVal = 'application/msword';
        }
        // DOCX
        else if ( $ext == 'docx' ) {
            $returnVal = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }
        // XLS
        else if ( $ext == 'xls' ) {
            $returnVal = 'application/vnd.ms-excel';
        }
        // XLSX
        else if ( $ext == 'xlsx' ) {
            $returnVal = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }
        // PPT
        else if ( $ext == 'ppt' ) {
            $returnVal = 'application/vnd.ms-powerpoint';
        }
        // PPTX
        else if ( $ext == 'pptx' ) {
            $returnVal = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        }
        // CSV
        else if ( $ext == 'csv' ) {
            $returnVal = 'text/csv';
        }
        
        return $returnVal;
    }
}
?>