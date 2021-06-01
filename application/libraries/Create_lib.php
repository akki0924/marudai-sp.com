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
    const CREATE_LOGIN_FILE = self::ADMIN_DIR . self::WEB_DIR_SEPARATOR . 'login';
    // PHPタグ修正
    const CHANGE_PHP_TAG_START = '\<\?';    // 開始タグ
    const CHANGE_PHP_TAG_END = '\?\>';      // 終了タグ
    // ID文字数
    const KEY_ID_STR_NUM = 'ID_STR_NUM';
    // マスターテーブル頭文字
    const MASTER_TABLE_PREFIX = 'm_';
    const MASTER_TABLE_PREFIX_NUM = 2;

    const SELECT_DISABLE_ALL = 'all';

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
     * 管理プログラム一覧ファイルの書出し処理
     *
     * @param string $strData：対象ファイルパス
     * @return void
     */
    public function CreateAdmin()
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // DBテーブル一覧を取得
        $tableList = $this->CI->db_lib->GetTables();

        // 読込みJSONファイルをセット
        $targetFile = self::JSON_DIR . self::WEB_DIR_SEPARATOR;
        $targetFile .= 'admin.php';
        // JSONファイルを読込み
        $jsonData = $this->CI->load->view($targetFile, '', true);
        // クォート処理
        $jsonData = $this->CI->json_lib->EscapeDoubleQuote($jsonData);
        Base_lib::ConsoleLog($jsonData);
        // JSONデコード
        $jsonVal = $this->CI->json_lib->Decode($jsonData);
        Base_lib::ConsoleLog($jsonVal);
        // 共通変数をセット
        $adminDir = self::ADMIN_DIR . self::WEB_DIR_SEPARATOR;
        // 管理プログラムに不必要なテーブルを削除
        if (
            isset($jsonVal['table']['disable']) &&
            count($jsonVal['table']['disable']) > 0
        ) {
            foreach ($jsonVal['table']['disable'] as $key => $val) {
                foreach ($tableList as $tableKey => $tableVal) {
                    if ($tableVal == $val) {
                        unset($tableList[$tableKey]);
                    }
                }
            }
            $tableList = array_values($tableList);
        }

        // 各テーブルのカラム情報を取得
        foreach ($tableList as $key => $val) {
            // カラム一覧をセット
            $table[$val] = $this->CI->db_lib->GetColumns($val);
            // カラムコメント一覧をセット
            $tableComment[$val] = $this->CI->db_lib->GetColumnComment($val);
            for ($i = 0, $n = count($tableComment[$val]); $i < $n; $i ++) {
                if (strpos($tableComment[$val][$i]['comment'], ' ') !== false) {
                    $tableComment[$val][$i]['comment'] = (substr($tableComment[$val][$i]['comment'], 0, strpos($tableComment[$val][$i]['comment'], ' ')));
                }
            }
        }
        $tableSel = $table;
        Base_lib::ConsoleLog($table);

        // 管理プログラムの登録に不必要なカラムを削除した配列を作成
        if (
            isset($jsonVal['table']['selectDisable']) &&
            count($jsonVal['table']['selectDisable']) > 0
        ) {
            foreach ($tableSel as $tableName => $columnKey) {
                foreach ($jsonVal['table']['selectDisable'] as $selTableName => $selColumnKey) {
                    // 特定テーブルの削除カラムを処理
                    if (
                        $selTableName != self::SELECT_DISABLE_ALL &&
                        $tableName == $selTableName
                    ) {
                        for ($t_i = 0, $t_n = count($columnKey); $t_i < $t_n; $t_i ++) {
                            for ($s_i = 0, $s_n = count($selColumnKey); $s_i < $s_n; $s_i ++) {
                                if ($columnKey[$t_i] == $selColumnKey[$s_i]) {
                                    // カラム情報を削除
                                    unset($tableSel[$tableName][$t_i]);
                                }
                            }
                        }
                    }
                    // 全テーブル共通の削除カラムを処理
                    if ($selTableName == self::SELECT_DISABLE_ALL) {
                        for ($t_i = 0, $t_n = count($columnKey); $t_i < $t_n; $t_i ++) {
                            for ($s_i = 0, $s_n = count($selColumnKey); $s_i < $s_n; $s_i ++) {
                                if ($columnKey[$t_i] == $selColumnKey[$s_i]) {
                                    // カラム情報を削除
                                    unset($tableSel[$tableName][$t_i]);
                                }
                            }
                        }
                    }
                }
                // 配列を整理
                $tableSel[$tableName] = array_values($tableSel[$tableName]);
            }
        }
        Base_lib::ConsoleLog($tableSel);

        // 生成リスト
        $createList = array(
            self::CONTROLLER_DIR,
            self::VIEW_DIR,
            self::MODEL_DIR,
            self::LIBRARY_DIR,
        );

        // ログイン情報を処理
        if (isset($jsonVal['login'])) {
            foreach ($createList as $createDir) {
                // controllersファイル生成
                if ($createDir == self::CONTROLLER_DIR) {
                    // 自動生成用テンプレートファイル
                    $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                    $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Index';
                    // 自動生成用テンプレート情報を取得
                    $writeVal = $this->CI->load->view($targetFile, $jsonVal['login'], true);
                    // PHPタグの置換
                    $writeVal = $this->ReturnPhpTag($writeVal);
                    // views出力先パス
                    $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                    $uploadPath .= $adminDir . 'Index.php';
                    // ディレクトリ生成
                    $this->CreateDir(dirname($uploadPath));
                    // ファイル出力
                    write_file($uploadPath, $writeVal);
                }
                // viewsファイル生成
                elseif ($createDir == self::VIEW_DIR) {
                    // 自動生成用テンプレートファイル
                    $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                    $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'login';
                    // 自動生成用テンプレート情報を取得
                    $writeVal = $this->CI->load->view($targetFile, $jsonVal['login'], true);
                    // PHPタグの置換
                    $writeVal = $this->ReturnPhpTag($writeVal);
                    // views出力先パス
                    $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                    $uploadPath .= $adminDir . 'login.php';
                    // ディレクトリ生成
                    $this->CreateDir(dirname($uploadPath));
                    // ファイル出力
                    write_file($uploadPath, $writeVal);
                }
                // modelsファイル生成
                elseif ($createDir == self::MODEL_DIR) {
                    // 自動生成用テンプレートファイル
                    $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                    $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Index_model';
                    // 自動生成用テンプレート情報を取得
                    $writeVal = $this->CI->load->view($targetFile, $jsonVal['login'], true);
                    // PHPタグの置換
                    $writeVal = $this->ReturnPhpTag($writeVal);
                    // views出力先パス
                    $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                    $uploadPath .= $adminDir . 'Index_model.php';
                    // ディレクトリ生成
                    $this->CreateDir(dirname($uploadPath));
                    // ファイル出力
                    write_file($uploadPath, $writeVal);
                }
                // librariesファイル生成
                elseif ($createDir == self::LIBRARY_DIR) {
                }
            }
        }

        // 管理画面内情報を処理
        foreach ($tableSel as $tableName => $columnKey) {
            // ログインマスタテーブル以外でかつ、マスターテーブルのみ
            if (
                $tableName != $jsonVal['login']['table'] &&
                substr($tableName, 0, self::MASTER_TABLE_PREFIX_NUM) == self::MASTER_TABLE_PREFIX
            ) {
                Base_lib::ConsoleLog('check1');
                // 対象名をセット
                $targetName = substr($tableName, self::MASTER_TABLE_PREFIX_NUM);
                $tempVal['targetName'] = $targetName;
                // テーブルコメントを取得
                $comment = $this->CI->db_lib->GetTableComment($tableName);
                $tempVal['comment'] = $this->GetCommentEdit($comment);
                // テーブルカラム情報をセット
                $tempVal['table'] = $table[$tableName];
                $tempVal['tableSel'] = $tableSel[$tableName];
                $tempVal['tableComment'] = $tableComment[$tableName];
                Base_lib::ConsoleLog($tempVal);
                foreach ($createList as $createDir) {
                    // controllersファイル生成
                    if ($createDir == self::CONTROLLER_DIR) {
                        // 自動生成用テンプレートファイル
                        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                        $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Target';
                        // 自動生成用テンプレート情報を取得
                        $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                        // PHPタグの置換
                        $writeVal = $this->ReturnPhpTag($writeVal);
                        // Base_lib::ConsoleLog($writeVal);
                        // views出力先パス
                        $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                        $uploadPath .= $adminDir . ucfirst($targetName) . '.php';
                        // ディレクトリ生成
                        $this->CreateDir(dirname($uploadPath));
                        // ファイル出力
                        write_file($uploadPath, $writeVal);
                    }
                    /*
                    // viewsファイル生成
                    elseif ($createDir == self::VIEW_DIR) {
                        // 自動生成用テンプレートファイル
                        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                        $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'login';
                        // 自動生成用テンプレート情報を取得
                        $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                        // PHPタグの置換
                        $writeVal = $this->ReturnPhpTag($writeVal);
                        Base_lib::ConsoleLog($writeVal);
                        // views出力先パス
                        $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                        $uploadPath .= $adminDir . 'login.php';
                        // ディレクトリ生成
                        $this->CreateDir(dirname($uploadPath));
                        // ファイル出力
                        write_file($uploadPath, $writeVal);
                    }
                    */
                    // modelsファイル生成
                    elseif ($createDir == self::MODEL_DIR) {
                        // 自動生成用テンプレートファイル
                        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                        $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Target_model';
                        // 自動生成用テンプレート情報を取得
                        $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                        // PHPタグの置換
                        $writeVal = $this->ReturnPhpTag($writeVal);
                        Base_lib::ConsoleLog($writeVal);
                        // views出力先パス
                        $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                        $uploadPath .= $adminDir . ucfirst($targetName) . '_model.php';
                        // ディレクトリ生成
                        $this->CreateDir(dirname($uploadPath));
                        // ファイル出力
                        write_file($uploadPath, $writeVal);
                    }
                    // librariesファイル生成
                    elseif ($createDir == self::LIBRARY_DIR) {
                    }
                }
            }
        }
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
     * ディレクトリが存在しない場合、生成
     *
     * @param string|null $dirPath 生成ディレクトリパス
     * @return bool
     */
    public function CreateDir(?string $dirPath = '') : bool
    {
        // 返値を初期化
        $returnVal = false;
        // 生成ディレクトリパスがセット
        if ($dirPath) {
            // 出力先のディレクトリパス
            $uploadDir = dirname(dirname(APPPATH) . self::WEB_DIR_SEPARATOR . $dirPath);

            // 出力ファイルの親ディレクトリが未存在の場合
            if (!file_exists($uploadDir)) {
                // ディレクトリを生成
                mkdir($uploadDir, 0755);
                $returnVal = true;
            }
        }
        return $returnVal;
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


    /**
     * 対象文字列から、変換された各PHPタグを元に戻す
     *
     * @param string|null $commentData 対象コメントデータ
     * @return string|null
     */
    public function GetCommentEdit(?string $commentData = '') : ? string
    {
        // コメントデータがセット
        if ($commentData) {
            // 変換されたタグを元に戻す
            $commentData = str_replace('情報', '', $commentData);
            $commentData = str_replace('マスタ', '', $commentData);
            $commentData = str_replace('データ', '', $commentData);
        }
        return $commentData;
    }
}