<?php
/*
■機　能 : ファイル操作用モデル
■概　要 : ファイル操作関連全般
■更新日 : 2020/07/21
■担　当 : crew.miwa

■更新履歴：
 2020/07/21 : 作成開始
*/

class File_model extends CI_Model {
    
    /*====================================================================
        コントラクト
    */
/*
    public function __construct(){
        $this->load->database();
    }
*/
    /*====================================================================
        関数名： DisplayImgFile
        概　要： 画像ファイルを表示
    */
    public function DisplayImgFile ( $filePath = '' ) {
        if ( $filePath != '' ) {
            // 一時ファイルが存在
            if ( $this->upload_lib->FileExists ( $filePath ) ) {
                // 一時ファイルパスを取得
                $filePath = $this->upload_lib->SrcPath( $filePath );
                // ヘッダの出力
                header( $this->GetContentType( $filePath ) );
                // ファイル
                print file_get_contents ( $filePath );
            }
        }
    }
    /*====================================================================
        関数名： GetMimeType
        概　要： 定義済み情報を元に指定されたファイルに対応するContent-Type文字列を取得
    */
    public static function GetMimeType ( $fileName ) {
        // 対象ファイル名の拡張子を取得
        $extension = pathinfo( $fileName, PATHINFO_EXTENSION );
        // 定義済みのMIMEタイプを取得
        $mimeTypes = file_get_contents ("/etc/mime.types");
        // リストからMIMEタイプを識別し値を取得
        if (preg_match ("/^([^\t\s]+).*?[\t\s]+(".$extension.")[\t\s\r\n]+/m", $mimeTypes, $matchValues)) {
            $returnVal = $matchValues[1];
        }

        return $returnVal;
    }

    /*====================================================================
        関数名： GetContentType
        概　要： 指定されたファイルに対応するContent-Type文字列を取得
        引　数：
                 arg1：  string/ファイル名
        戻り値： string/Content-Type文字列
    */
    public function GetContentType ( $fileName )
    {
        $imageType = getimagesize ( $fileName );
        if ( $imageType ) {
            $returnVal = "Content-type: ".image_type_to_mime_type ( $imageType[2] );
        }
        else if ( $this->IsPdfFile ( $fileName ) ) {
            $returnVal = "Content-type: application/pdf";
        }
        else {
            $returnVal = "";
        }
        
        return $returnVal;
    }

    /*====================================================================
        関数名： IsPdfFile
        概　要： 指定されたファイルがPDFファイルかどうかを判別する
        引　数：
                 arg1：  string/ファイル名
        戻り値： true:PDF形式／false:その他の形式
    */
    public function IsPdfFile ($fileName) {
        $fp = @fopen ($fileName, "r");
        if (!feof($fp)) {
            $header = fgets($fp, 4096);
        }
        fclose($fp);
        $returnVal = preg_match ("/^\%PDF\-[0-9\.]+/", $header);

        return $returnVal;
    }
    /*====================================================================
        関数名： DownloadAction
        概　要： ダウンロード処理
    */
    public function DownloadAction ( $filePath, $fileName = '' )
    {
        // 読み込み時間を延長
        ini_set('max_execution_time' ,'120');
        // 対象パスを取得
        $targetPath = $this->upload_lib->GetSrcPathVague ( $filePath );
        
        // ファイルタイプを指定
        header( 'Content-Type: application/force-download' );
        // ファイルサイズを取得し、ダウンロードの進捗を表示
        header ( 'Content-Length: ' . filesize( $targetPath ) );
        // ファイルのダウンロード、リネームを指示
        header ( 'Content-Disposition: attachment; filename="' . $fileName . '"' );
        // ファイルを読み込みダウンロードを実行
        readfile( $targetPath );
    }
}