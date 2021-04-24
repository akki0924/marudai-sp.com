<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 自動生成処理用ライブラリー
 *
 * PHPプログラム用のファイルを自動生成する為の関数群
 *
 * @author akki.m
 * @version 1.0.0
 * @since 1.0.0     2021/04/21  新規作成
 */
class Create_lib extends Base_lib
{
    /**
     * const
     */
    const TEMPLATE_DIR = 'create';                                                      // 自動生成用テンプレートディレクトリ
    const TEMPLATE_MODEL = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . 'model';      // モデル用テンプレート
    const TEMPLATE_LIBLARY = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . 'library';  // ライブラリー用テンプレート

    const JSON_DIR = Base_lib::JSON_DIR;
    const JSON_LIBRARY_DIR = self::JSON_DIR . self::WEB_DIR_SEPARATOR . Base_lib::LIBRARY_DIR;

    const CHANGE_PHP_TAG_START = '\<\?';
    const CHANGE_PHP_TAG_END = '\?\>';

    const KEY_ID_STR_NUM = 'ID_STR_NUM';
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
     * Libraryファイルの書出し処理
     *
     * @param string $strData：対象ファイルパス
     * @return void
     */
    public function CreateLibrary(string $filePath = '')
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 読込みJSONファイルをセット
        $targetFile = self::JSON_DIR . self::WEB_DIR_SEPARATOR;
        $targetFile .= $filePath;
        // JSONファイルを読込み
        $jsonData = $this->CI->load->view($targetFile, '', true);
        // JSONデコード
        $templateVal = json_decode($jsonData, true);
        // データ追加処理
        $templateVal['className'] = pathinfo(basename($targetFile), PATHINFO_FILENAME);
        $templateVal['CreateId_flg'] = false;
        if (isset($templateVal['constOnly'])) {
            for ($i = 0, $n = count($templateVal['constOnly']); $i < $n; $i ++) {
                if ($templateVal['constOnly'][$i]['key'] == self::KEY_ID_STR_NUM) {
                    $templateVal['CreateId_flg'] = true;
                    break;
                }
            }
        }
        Base_lib::ConsoleLog($jsonData);
        Base_lib::ConsoleLog($templateVal);

        // 希望エリア選択テンプレート読み込み
        $writeVal = $this->CI->load->view(self::TEMPLATE_LIBLARY, $templateVal, true);
        $writeVal = $this->ReturnPhpTag($writeVal);
        // 出力先パス
        $uploadPath = 'application/' . $filePath;
        // 出力先の親ディレクトリパス
        $uploadDir = dirname(dirname(APPPATH) . self::WEB_DIR_SEPARATOR . $uploadPath);
        Base_lib::ConsoleLog($uploadPath);
        Base_lib::ConsoleLog($uploadDir);
        // 出力ファイルの親ディレクトリが未存在の場合
        if (!file_exists($uploadDir)) {
            // ディレクトリを生成
            mkdir($uploadDir, 0755);
        }
        // ファイル出力
        write_file($uploadPath, $writeVal);
    }


    /**
     * Libraryファイル一覧の書出し処理
     *
     * @param string|null $strData
     * @return void
     */
    public function CreateLibraries() : void
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 対象ディレクトリ
        $targetDir = APPPATH . Base_lib::VIEW_DIR . self::WEB_DIR_SEPARATOR . self::JSON_LIBRARY_DIR;
        // ディレクトリ一覧を取得
        $dirList = $this->CI->file_lib->GetDirList($targetDir);
        Base_lib::ConsoleLog($dirList);
        foreach ($dirList as $dirVal) {
            // ディレクトリ内ファイル一覧
            $fileList = $this->CI->file_lib->GetNameList($targetDir . self::WEB_DIR_SEPARATOR . $dirVal);
            foreach ($fileList as $fileVal) {
                Base_lib::ConsoleLog($fileList);
                // JSONファイルパス
                $targetFile = Base_lib::LIBRARY_DIR . self::WEB_DIR_SEPARATOR . $dirVal;
                $targetFile .= self::WEB_DIR_SEPARATOR . $fileVal;
                // ファイル書出し処理
                $this->CreateLibrary($targetFile);
            }
        }
        // ファイル一覧
        $fileList = $this->CI->file_lib->GetNameList($targetDir);
        foreach ($fileList as $fileVal) {
            Base_lib::ConsoleLog($fileList);
            // JSONファイルパス
            $targetFile = Base_lib::LIBRARY_DIR . self::WEB_DIR_SEPARATOR . $fileVal;
            // ファイル書出し処理
            $this->CreateLibrary($targetFile);
        }
    }


    /**
     * 対象文字列から、変換された各PHPタグを元に戻す
     *
     * @param string|null $strData 対象文字列
     * @return string|null
     */
    public function ReturnPhpTag(?string $strData = '') : ? string
    {
        // 変換されたPHP開始タグが存在
        if (strpos($strData, self::CHANGE_PHP_TAG_START) !== false) {
            // 変換されたタグを元に戻す
            $strData = str_replace(self::CHANGE_PHP_TAG_START, '<?', $strData);
        }
        // 変換されたPHP終了タグが存在
        if (strpos($strData, self::CHANGE_PHP_TAG_END) !== false) {
            // 変換されたタグを元に戻す
            $strData = str_replace(self::CHANGE_PHP_TAG_END, '?>', $strData);
        }
        return $strData;
    }
}
