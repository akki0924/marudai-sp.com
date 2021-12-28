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
 * @version 1.1.2
 * @since 1.0.0     2021/04/21  新規作成
 * @since 1.0.1     2021/04/26  libraryファイル自動生成完成
 * @since 1.0.2     2021/05/10  libraryファイル一括自動生成機能追加
 * @since 1.0.3     2021/05/17  modelファイル自動生成機能追加
 * @since 1.0.4     2021/05/21  modelファイル一括自動生成機能追加
 * @since 1.1.0     2021/06/02  管理画面一括自動生成機能追加
 * @since 1.1.1     2021/06/11  管理画面一括自動生成機能にログ保存機能、実行前のファイル退避機能追加
 * @since 1.1.2     2021/07/09  管理画面一括自動生成機能のバグ修正（利用しないテーブル正しく設定されるように）
 *
 */
class Create_lib extends Base_lib
{
    /**
     * const
     */
    // テーブル名
    const LOG_TABLE = 'd_create_log';
    //　テーブルカラム名
    const LOG_COLUMN_MESSAGE = 'message';
    const LOG_COLUMN_BACKUP = 'backup_log';
    const LOG_COLUMN_CONTROLLER = 'controller_log';
    const LOG_COLUMN_VIEW = 'view_log';
    const LOG_COLUMN_MODEL = 'model_log';
    const LOG_COLUMN_LIBRARY = 'library_log';
    const LOG_MESSAGE_NOT_RUN = '自動実行が正しく処理出来ませんでした';
    const LOG_MESSAGE_ERROR = '自動実行中にエラーが発生しました';
    const LOG_MESSAGE_SUCCESS = '自動生成に成功しました';
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


    /**
     * var
     */
    private $logId;

    // スーパーオブジェクト割当用変数
    protected $CI;


    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // テーブル名をセット
        $this->SetDbTable(self::LOG_TABLE);
    }


    /**
     * 管理プログラム一覧ファイルの書出し処理
     *
     * 今後の更新予定内容
     * ・エラーページを他の管理画面に合わせて表示出来るように更新
     * ・実行画面にログを表示
     *
     * @return string|null
     */
    public function CreateAdmin() : ?string
    {
        // 必要なライブラリの読み込む
        $this->CI->load->library('admin_lib');
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // ログ保存処理
        $this->SetLogId($this->RegistLogAction());
        // ログデータ用変数を初期化
        $logData = array();
        // ログデータに初期メッセージをセット
        $logData[self::LOG_COLUMN_MESSAGE] = self::LOG_MESSAGE_NOT_RUN;
        // 不要なデータの退避処理
        $this->BackupAdminFile();
        // DBテーブル情報一覧を取得
        $tableList = $this->CI->db_lib->GetTablesData();
        for ($i = 0, $n = count($tableList); $i < $n; $i ++) {
            // コメント内容を更新
            $tableList[$i]['comment'] = $this->GetCommentEdit($tableList[$i]['comment']);
            // 対象名をセット
            $tableList[$i]['targetName'] = substr($tableList[$i]['name'], self::MASTER_TABLE_PREFIX_NUM);
        }
        // 読込みJSONファイルをセット
        $targetFile = self::JSON_DIR . self::WEB_DIR_SEPARATOR;
        $targetFile .= 'admin.php';
        // JSONファイルを読込み
        $jsonData = $this->CI->load->view($targetFile, '', true);
        // クォート処理
        $jsonData = $this->CI->json_lib->EscapeDoubleQuote($jsonData);
        // Base_lib::ConsoleLog($jsonData);
        // JSONデコード
        $jsonVal = $this->CI->json_lib->Decode($jsonData);
        // Base_lib::ConsoleLog($jsonVal);
        // 共通変数をセット
        $adminDir = self::ADMIN_DIR . self::WEB_DIR_SEPARATOR;
        // 管理プログラムに不必要なテーブルを削除
        if (
            isset($jsonVal['table']['disable']) &&
            count($jsonVal['table']['disable']) > 0
        ) {
            foreach ($jsonVal['table']['disable'] as $key => $val) {
                for ($i = 0, $n = count($tableList); $i < $n; $i ++) {
                    // 利用しないテーブル一覧の各テーブル名と登録テーブル名が一致
                    if (
                        isset($tableList[$i]['name']) &&
                        $tableList[$i]['name'] == $val
                    ) {
                        // 登録テーブル名を空に
                        $tableList[$i]['name'] = '';
                    }
                }
            }
            for ($i = 0, $n = count($tableList); $i < $n; $i ++) {
                // 登録テーブル名が空のもの
                if (! $tableList[$i]['name']) {
                    // 対象登録テーブル情報を削除
                    unset($tableList[$i]);
                }
            }
            // 配列キーの振り直し
            $tableList = array_values($tableList);
        }
        Base_lib::ConsoleLog($tableList);
        // ログイン用テーブルを一覧から削除
        for ($i = 0, $n = count($tableList); $i < $n; $i ++) {
            if ($tableList[$i]['name'] == Admin_lib::MASTER_TABLE) {
                unset($tableList[$i]);
            }
        }
        $tableList = array_values($tableList);
        Base_lib::ConsoleLog($tableList);

        // 各テーブルのカラム情報を取得
        for ($t_i = 0, $t_n = count($tableList); $t_i < $t_n; $t_i ++) {
            // カラムデータ一覧情報をセット
            $table[$tableList[$t_i]['name']] = $this->CI->db_lib->GetColumnsData($tableList[$t_i]['name']);
            for ($i = 0, $n = count($table[$tableList[$t_i]['name']]); $i < $n; $i ++) {
                // カラム名キャメルケース用の値をセット
                $table[$tableList[$t_i]['name']][$i]['name_camel'] = $this->GetCamelName($table[$tableList[$t_i]['name']][$i]['name']);
                // コメントを自動生成用に修正
                if (strpos($table[$tableList[$t_i]['name']][$i]['comment'], ' ') !== false) {
                    $table[$tableList[$t_i]['name']][$i]['comment'] = (substr($table[$tableList[$t_i]['name']][$i]['comment'], 0, strpos($table[$tableList[$t_i]['name']][$i]['comment'], ' ')));
                }
            }
        }
        $tableSel = $table;
        Base_lib::ConsoleLog($table);

        // 管理画面用のテーブル配列一覧を生成
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
                                if ($columnKey[$t_i]['name'] == $selColumnKey[$s_i]) {
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
                                if ($columnKey[$t_i]['name'] == $selColumnKey[$s_i]) {
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
        // 管理画面用のテーブル配列一覧からログイン用テーブルを省く
        unset($tableSel[Admin_lib::MASTER_TABLE]);

        // 生成リスト
        $createList = $this->GetMvcDirNameList();
        // viewファイル一覧
        $viewList = array(
            'list',
            'input',
            'conf',
            'comp',
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
                // 出力パスが設定
                if ($uploadPath) {
                    if ($createDir == self::CONTROLLER_DIR) {
                        $logColumn = self::LOG_COLUMN_CONTROLLER;
                    } elseif ($createDir == self::VIEW_DIR) {
                        $logColumn = self::LOG_COLUMN_VIEW;
                    } elseif ($createDir == self::MODEL_DIR) {
                        $logColumn = self::LOG_COLUMN_MODEL;
                    } elseif ($createDir == self::LIBRARY_DIR) {
                        $logColumn = self::LOG_COLUMN_LIBRARY;
                    }
                    // ログ用変数に追加
                    $logData[$logColumn][] = $uploadPath;
                    // ログデータのメッセージを更新
                    $logData[self::LOG_COLUMN_MESSAGE] = self::LOG_MESSAGE_ERROR;
                    // 出力パスを初期化
                    $uploadPath = '';
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
                // テーブル一覧情報をセット
                $tempVal['tableList'] = $tableList;
                // テーブル名をセット
                $tempVal['tableName'] = $tableName;
                // 対象名をセット
                $targetName = substr($tableName, self::MASTER_TABLE_PREFIX_NUM);
                $tempVal['targetName'] = $targetName;
                // テーブルコメントを取得
                $comment = $this->CI->db_lib->GetTableComment($tableName);
                $tempVal['comment'] = $this->GetCommentEdit($comment);
                // テーブルカラム情報をセット
                $tempVal['table'] = $table[$tableName];
                $tempVal['tableSel'] = $tableSel[$tableName];
                // クラス定数をセット
                $tempVal['const'] = $this->GetBaseConstList();

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
                        // views出力先パス
                        $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                        $uploadPath .= $adminDir . ucfirst($targetName) . '.php';
                        // ディレクトリ生成
                        $this->CreateDir(dirname($uploadPath));
                        // ファイル出力
                        write_file($uploadPath, $writeVal);
                    }
                    // viewsファイル生成
                    elseif ($createDir == self::VIEW_DIR) {
                        // 生成するviewsファイル分ループ
                        for ($v_i = 0, $v_n = count($viewList); $v_i < $v_n; $v_i ++) {
                            // 自動生成用テンプレートファイル
                            $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                            $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'target_' . $viewList[$v_i];
                            // 自動生成用テンプレート情報を取得
                            $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                            // PHPタグの置換
                            $writeVal = $this->ReturnPhpTag($writeVal);
                            // views出力先パス
                            $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                            $uploadPath .= $adminDir . $targetName . '_' . $viewList[$v_i] . '.php';
                            // ディレクトリ生成
                            $this->CreateDir(dirname($uploadPath));
                            // ファイル出力
                            write_file($uploadPath, $writeVal);
                        }
                    }
                    // modelsファイル生成
                    elseif ($createDir == self::MODEL_DIR) {
                        // 自動生成用テンプレートファイル
                        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                        $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Target_model';
                        // 自動生成用テンプレート情報を取得
                        $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                        // PHPタグの置換
                        $writeVal = $this->ReturnPhpTag($writeVal);
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
                        // 自動生成用テンプレートファイル
                        $targetFile = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . $adminDir;
                        $targetFile .= $createDir . self::WEB_DIR_SEPARATOR . 'Target_lib';
                        // 自動生成用テンプレート情報を取得
                        $writeVal = $this->CI->load->view($targetFile, $tempVal, true);
                        // PHPタグの置換
                        $writeVal = $this->ReturnPhpTag($writeVal);
                        // views出力先パス
                        $uploadPath = 'application/' . $createDir . self::WEB_DIR_SEPARATOR;
                        $uploadPath .= 'master/' . ucfirst($targetName) . '_lib.php';
                        // ディレクトリ生成
                        $this->CreateDir(dirname($uploadPath));
                        // ファイル出力
                        write_file($uploadPath, $writeVal);
                    }
                    // 出力パスが設定
                    if ($uploadPath) {
                        if ($createDir == self::CONTROLLER_DIR) {
                            $logColumn = self::LOG_COLUMN_CONTROLLER;
                        } elseif ($createDir == self::VIEW_DIR) {
                            $logColumn = self::LOG_COLUMN_VIEW;
                        } elseif ($createDir == self::MODEL_DIR) {
                            $logColumn = self::LOG_COLUMN_MODEL;
                        } elseif ($createDir == self::LIBRARY_DIR) {
                            $logColumn = self::LOG_COLUMN_LIBRARY;
                        }
                        // ログ用変数に追加
                        $logData[$logColumn][] = $uploadPath;
                        // 出力パスを初期化
                        $uploadPath = '';
                    }
                }
            }
        }
        // ログ用変数がセット
        if ($logData) {
            // 全てに更新情報が存在
            if (
                count($logData[self::LOG_COLUMN_CONTROLLER]) > 0 &&
                count($logData[self::LOG_COLUMN_VIEW]) > 0 &&
                count($logData[self::LOG_COLUMN_MODEL]) > 0 &&
                count($logData[self::LOG_COLUMN_LIBRARY]) > 0
            ) {
                // ログデータのコメントを更新
                $logData[self::LOG_COLUMN_MESSAGE] = self::LOG_MESSAGE_SUCCESS;
                // ログデータ表示用変数をセット
                $logDataDisp = $logData;
            }
            // ログ用変数をJSON化
            $logData[self::LOG_COLUMN_CONTROLLER] = json_encode(
                $logData[self::LOG_COLUMN_CONTROLLER],
                JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
            );
            $logData[self::LOG_COLUMN_VIEW] = json_encode(
                $logData[self::LOG_COLUMN_VIEW],
                JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
            );
            $logData[self::LOG_COLUMN_MODEL] = json_encode(
                $logData[self::LOG_COLUMN_MODEL],
                JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
            );
            $logData[self::LOG_COLUMN_LIBRARY] = json_encode(
                $logData[self::LOG_COLUMN_LIBRARY],
                JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
            );

            // ログ保存処理
            $this->RegistLogAction($logData, $this->GetLogId());
        }
        return ($this->GetLogId() ? $this->GetLogId() : '');
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


    /**
     * 不要な管理画面用各ファイルを退避処理
     *
     * @return void
     */
    public function BackupAdminFile() : void
    {
        // MCVディレクトリ名一覧
        $dirList = $this->GetMvcDirNameList();
        // 対象ファイル一覧
        $targetList = array();
        // 対象外ファイル一覧
        $otherList[self::CONTROLLER_DIR] = array(
            'Error.php',
        );
        $otherList[self::VIEW_DIR] = array(
            'error.php',
        );
        for ($i = 0, $n = count($dirList); $i < $n; $i ++) {
            // 対象ディレクトリパス
            $dirPath = APPPATH . $dirList[$i] . DIRECTORY_SEPARATOR;
            // ライブラリーディレクトリ以外
            if ($dirList[$i] != self::LIBRARY_DIR) {
                $dirPath .= self::ADMIN_DIR . DIRECTORY_SEPARATOR;
            }
            // ライブラリーディレクトリ
            else {
                $dirPath .= self::MASTER_DIR . DIRECTORY_SEPARATOR;
            }
            foreach (glob($dirPath . "*") as $fileName) {
                // 対象外ファイルとの比較用ファイル名を取得
                $otherCheckFile = substr($fileName, (strrpos($fileName, '\\') + 1));
                if (
                    !isset($otherList[$dirList[$i]]) ||
                    !in_array($otherCheckFile, $otherList[$dirList[$i]])
                ) {
                    // 削除対象のファイルを配列にセット
                    $targetList[$dirList[$i]][] = $fileName;
                }
            }
        }
        // ログ用変数をJSON化
        $registData[self::LOG_COLUMN_BACKUP] = json_encode($targetList, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        // ログ保存処理
        $this->RegistLogAction($registData, $this->GetLogId());
        // 退避処理
        $backupDir = dirname(APPPATH) . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . date('Ymd');
        // 親ディレクトリ生成
        $this->CI->file_lib->CreateDir($backupDir);
        foreach ($targetList as $key => $val) {
            for ($i = 0, $n = count($val); $i < $n; $i ++) {
                $tmpDir = $backupDir . DIRECTORY_SEPARATOR . $key;
                // ディレクトリ生成
                $this->CI->file_lib->CreateDir($tmpDir);
                // library以外
                if ($key != self::LIBRARY_DIR) {
                    // ディレクトリ生成
                    $this->CI->file_lib->CreateDir($tmpDir . DIRECTORY_SEPARATOR . self::ADMIN_DIR);
                    // ファイル退避処理
                    rename(
                        $val[$i],
                        $tmpDir . DIRECTORY_SEPARATOR . self::ADMIN_DIR . DIRECTORY_SEPARATOR . substr($val[$i], (strrpos($val[$i], '\\') + 1))
                    );
                }
                // library
                else {
                    // ディレクトリ生成
                    $this->CI->file_lib->CreateDir($tmpDir . DIRECTORY_SEPARATOR . self::MASTER_DIR);
                    // ファイル退避処理
                    rename(
                        $val[$i],
                        $tmpDir . DIRECTORY_SEPARATOR . self::MASTER_DIR . DIRECTORY_SEPARATOR . substr($val[$i], (strrpos($val[$i], '\\') + 1))
                    );
                }
            }
        }
    }


    /**
     * MVCディレクトリ名一覧情報を取得
     *
     * @return array
     */
    public function GetMvcDirNameList() : array
    {
        $returnList = array(
            self::CONTROLLER_DIR,
            self::VIEW_DIR,
            self::MODEL_DIR,
            self::LIBRARY_DIR,
        );
        return $returnList;
    }


    /**
     * ログ登録処理
     *
     * @param array|null $dataList：バリデーションフラグ
     * @param string|null $id：対象ID
     * @return string
     */
    public function RegistLogAction($dataList = array(), $id = '') : ?string
    {
        // 返値を初期化
        $returnVal = '';
        // 対象IDがセット
        if ($id) {
            // バッグアップ登録
            if (isset($dataList[self::LOG_COLUMN_BACKUP])) {
                $registData[self::LOG_COLUMN_BACKUP] = $dataList[self::LOG_COLUMN_BACKUP];
            }
            // controller登録
            if (isset($dataList[self::LOG_COLUMN_CONTROLLER])) {
                $registData[self::LOG_COLUMN_CONTROLLER] = $dataList[self::LOG_COLUMN_CONTROLLER];
            }
            // view登録
            if (isset($dataList[self::LOG_COLUMN_VIEW])) {
                $registData[self::LOG_COLUMN_VIEW] = $dataList[self::LOG_COLUMN_VIEW];
            }
            // model登録
            if (isset($dataList[self::LOG_COLUMN_MODEL])) {
                $registData[self::LOG_COLUMN_MODEL] = $dataList[self::LOG_COLUMN_MODEL];
            }
            // library登録
            if (isset($dataList[self::LOG_COLUMN_LIBRARY])) {
                $registData[self::LOG_COLUMN_LIBRARY] = $dataList[self::LOG_COLUMN_LIBRARY];
            }
            if (
                isset($registData) &&
                count($registData) > 0
            ) {
                // 登録処理
                $returnVal = $this->Regist($registData, $id);
            }
        }
        // 初期化
        else {
            // 初期登録処理
            $returnVal = $this->Regist(array());
        }
        return $returnVal;
    }


    /**
     * ログID情報（メンバー変数）をセット
     *
     * @param string $logId：対象データ
     * @return void
     */
    public function SetLogId(string $logId = '') : void
    {
        $this->logId = $logId;
    }


    /**
     * ログID情報（メンバー変数）を取得
     *
     * @return string|null
     */
    public function GetLogId() : string
    {
        return $this->logId;
    }


    /**
     * ログID情報（メンバー変数）の確認
     *
     * @return bool
     */
    public function CheckLogId() : bool
    {
        return ($this->logId ? true : false);
    }


    /**
     * IDに対応したログ詳細データを取得
     *
     * @param string $id：ID
     * @return array|null
     */
    public function GetLogDetailValues(string $id = '') : ?array
    {
        // 返値を初期化
        $returnVal = array();
        // SQL
        $query = $this->CI->db->query("
            SELECT
                " . self::LOG_TABLE . " . id,
                " . self::LOG_TABLE . " . message,
                " . self::LOG_TABLE . " . backup_log,
                " . self::LOG_TABLE . " . controller_log,
                " . self::LOG_TABLE . " . view_log,
                " . self::LOG_TABLE . " . model_log,
                " . self::LOG_TABLE . " . library_log,
                " . self::LOG_TABLE . " . regist_date,
                DATE_FORMAT(" . self::LOG_TABLE . " . regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . self::LOG_TABLE . " . edit_date,
                DATE_FORMAT(" . self::LOG_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
            FROM " . self::LOG_TABLE . "
            WHERE (
                " . self::LOG_TABLE . " . id = " . $this->CI->db_lib->SetWhereVar($id) . "
            )
            ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $resultList = $query->result_array();
            foreach ($resultList[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        return $returnVal;
    }


    /**
     * ログデータを表示用に変換して書出すID情報（メンバー変数）の確認
     *
     * @param string $logId：対象データ
     * @return string|null
     */
    public function GetLogDataDisp($logData) : ?string
    {
        // 返値を初期化
        $returnVal = '';
        // メッセージ
        $returnVal .= '◆実行結果：' . $logData[self::LOG_COLUMN_MESSAGE] . '<br>';
        $returnVal .= '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>';
        $returnVal .= '◆実行内容：<br>';
        // バックアップ
        if ($logData[self::LOG_COLUMN_BACKUP]) {
            $tempVal = json_decode($logData[self::LOG_COLUMN_BACKUP], true);
            $returnVal .= '　○バックアップログ<br>';
            if (count($tempVal) > 0) {
                Base_lib::ConsoleLog($tempVal);
                foreach ($tempVal as $key => $val) {
                    $returnVal .= '　　' . $key . '：<br>';
                    for ($i = 0, $n = count($tempVal[$key]); $i < $n; $i ++) {
                        $returnVal .= '　　　' . $tempVal[$key][$i] . '<br>';
                    }
                }
            }
        }
        // コントローラー
        if ($logData[self::LOG_COLUMN_CONTROLLER]) {
            $tempVal = json_decode($logData[self::LOG_COLUMN_CONTROLLER], true);
            $returnVal .= '　○コントローラーログ<br>';
            if (count($tempVal) > 0) {
                for ($i = 0, $n = count($tempVal); $i < $n; $i ++) {
                    //
                    $returnVal .= '　　' . $tempVal[$i] . '<br>';
                }
            }
        }
        // ビュー
        if ($logData[self::LOG_COLUMN_VIEW]) {
            $tempVal = json_decode($logData[self::LOG_COLUMN_VIEW], true);
            $returnVal .= '　○ビューログ<br>';
            if (count($tempVal) > 0) {
                for ($i = 0, $n = count($tempVal); $i < $n; $i ++) {
                    //
                    $returnVal .= '　　' . $tempVal[$i] . '<br>';
                }
            }
        }
        // モデル
        if ($logData[self::LOG_COLUMN_MODEL]) {
            $tempVal = json_decode($logData[self::LOG_COLUMN_MODEL], true);
            $returnVal .= '　○モデルログ<br>';
            if (count($tempVal) > 0) {
                for ($i = 0, $n = count($tempVal); $i < $n; $i ++) {
                    //
                    $returnVal .= '　　' . $tempVal[$i] . '<br>';
                }
            }
        }
        // ライブラリー
        if ($logData[self::LOG_COLUMN_LIBRARY]) {
            $tempVal = json_decode($logData[self::LOG_COLUMN_LIBRARY], true);
            $returnVal .= '　○ライブラリーログ<br>';
            if (count($tempVal) > 0) {
                for ($i = 0, $n = count($tempVal); $i < $n; $i ++) {
                    //
                    $returnVal .= '　　' . $tempVal[$i] . '<br>';
                }
            }
        }
        return $returnVal;
    }
}
