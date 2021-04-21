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
 * @since 1.0.0     2021/02/27  新規作成
 */
class Create_lib extends Base_lib
{
    /**
     * const
     */
    const TEMPLATE_DIR = 'create';                                                      // 自動生成用テンプレートディレクトリ
    const TEMPLATE_MODEL = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . 'model';      // モデル用テンプレート
    const TEMPLATE_LIBLARY = self::TEMPLATE_DIR . self::WEB_DIR_SEPARATOR . 'library';  // ライブラリー用テンプレート

    const CHANGE_PHP_TAG_START = '\<\?';
    const CHANGE_PHP_TAG_END = '\?\>';
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
     * JS形式にして書出し処理
     *
     * @param string|null $strData
     * @return void
     */
    public function CreateLiblary(?string $strData = '')
    {
        // ヘルパー関数読込み
        $this->CI->load->helper('file');
        // 日本語名
        $set['name'] = 'テスト';
        // クラス名（ファイル名）
        $set['fileName'] = 'Test';
        // テーブル名
        $set['tableName'] = 'm_test';
        // カラム一覧
        $set['columnList'] = array(
            'id',
            'name',
            'sort_id',
            'status',
            'regist_date',
            'edit_date',
        );
        // 個別取得カラム一覧
        $set['selectList'] = array(
            array(
                'key' => 'Name',
                'name' => 'name',
                'title' => '名前',
            ),
            array(
                'key' => 'SortId',
                'name' => 'sort_id',
                'title' => '順番'
            ),
        );
        // 定数一覧
        $set['constList'] = array(
            'STATUS' => array(
                'comment' => '表示ステータス',
                'key' => 'Status',
                'data' => array(
                    array(
                        'key' => 'OK',
                        'id' => 1,
                        'name' => '大丈夫だよ',
                    ),
                    array(
                        'key' => 'NG',
                        'id' => -1,
                        'name' => '大丈夫じゃないよ',
                    ),
                ),
            ),
        );
        Base_lib::ConsoleLog($set);
        // 希望エリア選択テンプレート読み込み
        $test = $this->CI->load->view(self::TEMPLATE_LIBLARY, $set, true);
        $test = $this->ReturnPhpTag($test);

        // 出力先
        write_file('application/libraries/create_test.php', $test);
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
