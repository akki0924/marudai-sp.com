<?php
/**
 * CSV用ライブラリー
 *
 * CSVの取得および処理する為の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2019/11/19：新規作成
 * @since 1.0.1     2022/02/25：ファイルパスにドキュメントルート（サーバー関数）を初期値に追加
 */
class Csv_lib
{
    // メンバー変数
    protected $CI;                          // スーパーオブジェクト割当用
    private $targetFilePath;                // 対象ファイルパス
    private $targetCsvObj;                  // 対象CSVオブジェクト

    /**
     * コントラクト
     */
    public function __construct($params = array())
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // 対象キーが引数にセットされている場合
        if (isset($params['path']) && $params['path'] != '') {
            // ファイルパスにドキュメントルートを追加
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $params['path'];
            Base_lib::ConsoleLog($filePath);
            // 対象ファイルパス情報セットする
            $this->SetFilePath($filePath);
            // CSVオブジェクトを宣言
            $this->targetCsvObj = new SplFileObject($filePath);
            $this->targetCsvObj->setFlags(SplFileObject::READ_CSV);
        }
    }
    /*====================================================================
        関数名： GetList
        概　要： データ一覧を取得
        引　数： $public : ステータスフラグ
    */
    public function GetList($public = false)
    {
        $returnVal = array();
        foreach ($this->targetCsvObj as $line) {
            Base_lib::ConsoleLog('list s');
            Base_lib::ConsoleLog($line);
            Base_lib::ConsoleLog('list e');
            if (is_array($line)) {
                for ($i = 0, $n = count($line); $i < $n; $i ++) {
                    if ($line[$i]) {
                        $line[$i] = $this->CI->str_lib->GetConvertUtf8($line[$i]);
                    }
                }
                $returnVal[] = $line;
            } else {
                if ($line) {
                    $returnVal[] = $this->CI->str_lib->GetConvertUtf8($line);
                } else {
                    $returnVal[] = '';
                }
            }

            //mb_convert_variables('UTF-8', array( 'SJIS-win' ), $line);
            //$returnVal[] = $line;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： CheckData
        概　要： CSVの検証確認
    */
    public function CheckData($filePath = '')
    {
        // 戻り値を初期化
        $returnVal = false;
        // 対象ファイルパスがセットされていない場合、クラス宣言時のファイルパスを利用
        $filePath = ($filePath != '' ? $filePath : $this->GetFilePath());

        // ファイルがアップロードされているか
        if ($filePath != '' && file_exists($filePath)) {
            // MINEタイプを取得
            $mime = get_mime_by_extension($filePath);
            // MIMEタイプが許可されたものに限り、戻り値をTRUEに更新
            if (in_array($mime, $this->GetMineType())) {
                $returnVal = true;
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetMineType
        概　要： CSVのMINEタイプを取得
    */
    public function GetMineType()
    {
        return array( 'text/csv', 'text/comma-separated-values', 'text/x-comma-separated-values' );
    }
    /*====================================================================
        関数名： SetFilePath
        概　要： 対象ファイルパス情報をセット
    */
    public function SetFilePath($path)
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
