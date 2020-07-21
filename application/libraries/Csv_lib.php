<?php
/*
■機　能： CSV用ライブラリー
■概　要：
■更新日：
■担　当： crew.miwa

■更新履歴：
 2019/11/19: 作成開始
*/

class Csv_lib
{
    // メンバー変数
    protected $CI;                          // スーパーオブジェクト割当用
    private $targetFilePath;                // 対象ファイルパス
    private $targetCsvObj;                  // 対象CSVオブジェクト
    /*====================================================================
        コントラクト
    */
    public function __construct( $params = array () )
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // 対象キーが引数にセットされている場合
        if ( isset ( $params['path'] ) && $params['path'] != '' ) {
            // 対象ファイルパス情報セットする
            $this->SetFilePath ( $params['path'] );
            // CSVオブジェクトを宣言
            $this->targetCsvObj = new SplFileObject ( $params['path'] );
            $this->targetCsvObj->setFlags ( SplFileObject::READ_CSV );
        }
    }
    /*====================================================================
        関数名： GetList
        概　要： データ一覧を取得
        引　数： $public : ステータスフラグ
    */
    public function GetList ( $public = false )
    {
        $returnVal = array ();
        
        foreach ( $this->targetCsvObj as $line ) {
            mb_convert_variables( 'UTF-8', array ( 'SJIS-win' ), $line );
            $returnVal[] = $line;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： CheckData
        概　要： CSVの検証確認
    */
    public function CheckData ( $filePath = '' ) {
        // 戻り値を初期化
        $returnVal = false;
        // 対象ファイルパスがセットされていない場合、クラス宣言時のファイルパスを利用
        $filePath = ( $filePath != '' ? $filePath : $this->GetFilePath () );
        
        // ファイルがアップロードされているか
        if ( $filePath != '' && file_exists ( $filePath ) ) {
            // MINEタイプを取得
            $mime = get_mime_by_extension( $filePath );
            // MIMEタイプが許可されたものに限り、戻り値をTRUEに更新
            if ( in_array( $mime, $this->GetMineType () ) ) $returnVal = true;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetMineType
        概　要： CSVのMINEタイプを取得
    */
    public function GetMineType ()
    {
        return array( 'text/csv', 'text/comma-separated-values', 'text/x-comma-separated-values' );
    }
    /*====================================================================
        関数名： SetFilePath
        概　要： 対象ファイルパス情報をセット
    */
    public function SetFilePath( $path )
    {
        $this->targetFilePath = $path;
    }
    /*====================================================================
        関数名： GetFilePath
        概　要： 対象ファイルパス情報を取得
    */
    public function GetFilePath()
    {
        return $this->targetFilePath;
    }
}
