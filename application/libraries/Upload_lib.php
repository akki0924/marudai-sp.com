<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： アップロード用サポート処理ライブラリー
    ■概　要： アップロード用登録関数群
    ■更新日： 2018/01/22
    ■担　当： crew.miwa

    ■更新履歴：
     2018/01/22: 作成開始
     
    */

class Upload_lib
{
    // SRCパス
    const SRC_DIR = "src";
    // 一時的SRCパス
    const SRC_TEMP_DIR = "tmp";
    // リサイズ
    const RESIZE_WIDTH = 720;
    const RESIZE_HEIGHT = 720;
    // 保存ディレクトリー
    // ノーイメージファイル名（1ドットgif画像）
    const NO_IMAGE_FILE = "noimage";
    // ディレクトリ作成時のパーミッション
    const PERMISSION_DIR = "0755";
    // ID生成用文字数
    const CREATE_ID_STRNUM = 10;
    // ファイル権限
    const CREATE_FILE_AUTH = "0666";
    
    const WEB_DIRECTORY_SEPARATOR = "/";
    
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct(){
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名： SrcPath
        概　要： アップロードディレクトリを取得
    */
    function SrcPath ( $file = '' )
    {
        return realpath ( APPPATH . "../" . self::SRC_DIR ) . ( $file != "" ? DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名： SrcTempPath
        概　要： アップロード仮登録用ディレクトリを取得
    */
    function SrcTempPath ( $file = '' )
    {
        return $this->SrcPath () . DIRECTORY_SEPARATOR . self::SRC_TEMP_DIR  . ( $file != "" ? DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名： SrcWebPath
        概　要： アップロードディレクトリを取得
    */
    function SrcWebPath ( $file = '' )
    {
        return D_ROOT . self::SRC_DIR . ( $file != "" ? self::WEB_DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名： SrcWebTempPath
        概　要： アップロード仮登録用ディレクトリを取得
    */
    function SrcWebTempPath ( $file = '' )
    {
        return $this->SrcWebPath () . self::WEB_DIRECTORY_SEPARATOR . self::SRC_TEMP_DIR  . ( $file != "" ? self::WEB_DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名： DoUpload
        概　要： 画像アップロード処理
        引　数： $name：form名
                 $file_name：ファイル名
    */
    function DoUpload ( $name, $file_name = "" )
    {
        // ディレクトリとファイル名を配列に分割
        $file_name_array = @explode ( DIRECTORY_SEPARATOR, $file_name );
        // ファイルアップロードをセット
        $upload_path = $this->SrcPath() . DIRECTORY_SEPARATOR . $file_name_array[0];
        // ディレクトリが存在しない場合
        if ( ! file_exists ( $upload_path ) )
        {
            mkdir ( $upload_path );
//            chmod ($upload_path, self::PERMISSION_DIR);
        }
        // アップロード - 設定情報
        $upload_config = array (
            "allowed_types" => "jpg|jpeg|gif|png", // ファイルのアップロード制限
            "upload_path" => $upload_path,         // ファイルのアップロード先（ユーザー毎のディレクトリを追加）
            "overwrite" => TRUE,                   // 上書き設定
            "xss_clean" => TRUE,                   // XSSのフィルタリング
            "file_name" => $file_name_array[1]     // ファイル名
        );
        // アップロードクラス初期化
        $this->upload->initialize( $upload_config );
        // アップロードライブラリー読込み
//        $this->load->library("upload", $upload_config);
        
        // アップロード処理の実行
        if ( ! $this->upload->do_upload( $name ) )
        {
            // エラー時、エラー内容をセット
            $returnVal = array( 'error' => $this->upload->display_errors() );
        }
        // アップロード成功時
        else
        {
            // アップロード情報をセット
            $returnVal = $this->upload->data();
            // 拡張子を抜いたファイル名をセット
            $change_name = $returnVal['file_path'] . $returnVal['raw_name'];
            // 対象ファイルをリネーム（拡張子なし）
            rename ( $returnVal['full_path'], $change_name );
            // 画像操作 - 設定情報
            $imagelib_config = array (
                "image_library" => "gd2",          // 画像操作ライブラリーを指定
                "source_image" => $change_name,    // 処理元ファイル
                "maintain_ratio" => TRUE,          // 縦横比の維持
                "create_thumb" => FALSE,           // サムネイルの作成有無
                "width" => self::RESIZE_WIDTH,     // 横幅
                "height" => self::RESIZE_HEIGHT,   // 縦幅
            );
            // 画像操作クラス初期化
            $this->image_lib->initialize( $imagelib_config );
            // 画像操作ライブラリー読込み
//            $this->load->library('image_lib', $imagelib_config);
            // リサイズ処理の実行
            if ( ! $this->image_lib->resize() )
            {
                // エラー時、エラー内容をセット
                $returnVal = array( 'error' => $this->image_lib->display_errors() );
            }
            
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： FileCheck
        概　要： 画像ファイルが存在しない場合、空画像をセット
        引　数： $name：ファイル名
    */
    function FileCheck ( $name = "" )
    {
        if ( file_exists ( $this->SrcPath() . DIRECTORY_SEPARATOR . $name ) )
        {
            return $name;
        }
        else {
            return self::NO_IMAGE_FILE;
        }
    }
    /*====================================================================
        関数名： UploadFileTemp
        概　要： 仮登録用ファイルを書込み
        引　数： $img_name：画像ファイル名
    */
    function UploadFileTemp ( $img_name = "" )
    {
        // 返値を初期化
        $returnVal = false;
        // ファイルがアップロードされている場合
        if ( isset ( $_FILES[$img_name] ) && $_FILES[$img_name]['error'] == 0 )
        {
            // ファイル名を生成
            $file_name = $this->CreateTempFileName ();
            // 仮登録用ファイルパスをセット
            $file_path = $this->SrcTempPath() . DIRECTORY_SEPARATOR . $file_name;
            // コピー処理
            $copy_flg = copy ( $_FILES[$img_name]['tmp_name'], $file_path );
            if ( $copy_flg )
            {
                // コピー先のファイル権限を変更
                chmod ( $file_path, self::CREATE_FILE_AUTH );
                // 返値にファイル名セット
                $returnVal = $file_name;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： UploadFileMain
        概　要： 本登録用ファイルを書込み
        引　数： $temp_file：仮登録用ファイル, $taget_file : 本登録用ファイル
    */
    function UploadFileMain ( $temp_file, $taget_file = "" )
    {
        // 返値を初期化
        $returnVal = false;
        
        // 仮登録用ファイルの修正
        $temp_file_array = explode ( self::WEB_DIRECTORY_SEPARATOR, $temp_file );
        if ( count ( $temp_file_array ) > 0 )
        {
            $temp_file  = $this->SrcTempPath( $temp_file_array[ max( array_keys ( $temp_file_array ) ) ] );
        }
        // ファイルが移動元に存在する場合
        if ( file_exists ( $temp_file ) )
        {
            // アップロード先ファイル名をセット
            $upload_file = $this->SrcPath () . DIRECTORY_SEPARATOR . $taget_file;
            
            // 移動処理
            $rename_flg = rename ( $temp_file, $upload_file );
            if ( $rename_flg )
            {
                // 移動先のファイル権限を変更
                chmod ( $upload_file, self::CREATE_FILE_AUTH );
                // 返値をセット
                $returnVal = true;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： CreateTempFileName
        概　要： 仮登録用ファイル名生成
    */
    public function CreateTempFileName ()
    {
        // 文字列用ヘルパー関数
        $this->CI->load->helper('string');
        do{
            // ファイル名生成
            $file = random_string('alnum', self::CREATE_ID_STRNUM);
        }
        // ファイル名が存在する場合、繰り返す
        while ( $this->TempFileExists ( $file ) );
        
        return $file;
    }
    /*====================================================================
        関数名： FileExists
        概　要： 登録用ファイルの存在確認
    */
    public function FileExists ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( file_exists ( $this->SrcPath() . DIRECTORY_SEPARATOR . $file  ) )
        {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： FileDelete
        概　要： ファイルの削除
    */
    public function FileDelete ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( unlink ( $this->SrcPath() . DIRECTORY_SEPARATOR . $file  ) )
        {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： TempFileExists
        概　要： 仮登録用ファイルの存在確認
    */
    public function TempFileExists ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( file_exists ( $this->SrcTempPath() . DIRECTORY_SEPARATOR . $file  ) )
        {
            $returnVal = true;
        }
        
        return $returnVal;
    }
}
?>