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
    // 自動生成用テンプレートディレクトリ
    const TEMPLATE_DIR = 'create';
    // 自動生成用テンプレートファイル
    const CREATE_MODEL_FILE = 'model';      // モデル用
    const CREATE_LIBRARY_FILE = 'library';  // ライブラリー用
    // PHPタグ修正
    const CHANGE_PHP_TAG_START = '\<\?';    // 開始タグ
    const CHANGE_PHP_TAG_END = '\?\>';      // 終了タグ
    // ID文字数
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
     * Modelファイルの書出し処理
     *
     * @param string $strData：対象ファイルパス
     * @return void
     */
    public function CreateModel(string $filePath = '')
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 読込みJSONファイルをセット
        $targetFile = self::JSON_DIR . self::WEB_DIR_SEPARATOR;
        $targetFile .= $filePath;
        // JSONファイルを読込み
        $jsonData = $this->CI->load->view($targetFile, '', true);
        // クォート処理
        $jsonData = $this->CI->json_lib->EscapeDoubleQuote($jsonData);
        Base_lib::ConsoleLog($jsonData);
        // JSONデコード
        $templateVal = $this->CI->json_lib->Decode($jsonData);
        Base_lib::ConsoleLog($templateVal);
        // データ追加処理
        $templateVal['className'] = pathinfo(basename($targetFile), PATHINFO_FILENAME);
        // 自動生成用テンプレートファイル
        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . self::CREATE_MODEL_FILE;
        // 自動生成用テンプレート読み込み
        $writeVal = $this->CI->load->view($targetFile, $templateVal, true);
        $writeVal = $this->ReturnPhpTag($writeVal);
        // 出力先パス
        $uploadPath = 'application/' . $filePath;
        // 出力先の親ディレクトリパス
        $uploadDir = dirname(dirname(APPPATH) . self::WEB_DIR_SEPARATOR . $uploadPath);
        // 出力ファイルの親ディレクトリが未存在の場合
        if (!file_exists($uploadDir)) {
            // ディレクトリを生成
            mkdir($uploadDir, 0755);
        }
        // ファイル出力
        write_file($uploadPath, $writeVal);
    }


    /**
     * Modelsファイル一覧の書出し処理
     *
     * @return void
     */
    public function CreateModels() : void
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 自動生成ディレクトリ
        $targetDir = APPPATH . self::VIEW_DIR . self::WEB_DIR_SEPARATOR;
        $targetDir .= self::JSON_DIR . self::WEB_DIR_SEPARATOR . self::MODEL_DIR;
        Base_lib::ConsoleLog($targetDir);
        // ディレクトリ一覧を取得
        $dirList = $this->CI->file_lib->GetDirList($targetDir);
        foreach ($dirList as $dirVal) {
            // ディレクトリ内ファイル一覧
            $fileList = $this->CI->file_lib->GetNameList($targetDir . self::WEB_DIR_SEPARATOR . $dirVal);
            foreach ($fileList as $fileVal) {
                // JSONファイルパス
                $targetFile = self::MODEL_DIR . self::WEB_DIR_SEPARATOR . $dirVal;
                $targetFile .= self::WEB_DIR_SEPARATOR . $fileVal;
                // ファイル書出し処理
                $this->CreateModel($targetFile);
            }
        }
        // ファイル一覧
        $fileList = $this->CI->file_lib->GetNameList($targetDir);
        foreach ($fileList as $fileVal) {
            // JSONファイルパス
            $targetFile = self::MODEL_DIR . self::WEB_DIR_SEPARATOR . $fileVal;
            // ファイル書出し処理
            $this->CreateModel($targetFile);
        }
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
        $templateVal = $this->CI->json_lib->Decode($jsonData);
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
        // 自動生成用テンプレートファイル
        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . self::CREATE_LIBRARY_FILE;
        // 自動生成用テンプレート読み込み
        $writeVal = $this->CI->load->view($targetFile, $templateVal, true);
        $writeVal = $this->ReturnPhpTag($writeVal);
        // 出力先パス
        $uploadPath = 'application/' . $filePath;
        // 出力先の親ディレクトリパス
        $uploadDir = dirname(dirname(APPPATH) . self::WEB_DIR_SEPARATOR . $uploadPath);
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
     * @return void
     */
    public function CreateLibraries() : void
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 自動生成ディレクトリ
        $targetDir = APPPATH . self::VIEW_DIR . self::WEB_DIR_SEPARATOR;
        $targetDir .= self::JSON_DIR . self::WEB_DIR_SEPARATOR . self::LIBRARY_DIR;
        // ディレクトリ一覧を取得
        $dirList = $this->CI->file_lib->GetDirList($targetDir);
        foreach ($dirList as $dirVal) {
            // ディレクトリ内ファイル一覧
            $fileList = $this->CI->file_lib->GetNameList($targetDir . self::WEB_DIR_SEPARATOR . $dirVal);
            foreach ($fileList as $fileVal) {
                // JSONファイルパス
                $targetFile = self::LIBRARY_DIR . self::WEB_DIR_SEPARATOR . $dirVal;
                $targetFile .= self::WEB_DIR_SEPARATOR . $fileVal;
                // ファイル書出し処理
                $this->CreateLibrary($targetFile);
            }
        }
        // ファイル一覧
        $fileList = $this->CI->file_lib->GetNameList($targetDir);
        foreach ($fileList as $fileVal) {
            // JSONファイルパス
            $targetFile = self::LIBRARY_DIR . self::WEB_DIR_SEPARATOR . $fileVal;
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
