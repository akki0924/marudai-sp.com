<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能 : アップロード用サポート処理ライブラリー
    ■概　要 : アップロード用登録関数群
    ■更新日 : 2020/02/06
    ■担　当 : crew.miwa

    ■更新履歴：
     2018/01/22 : 作成開始
     2020/02/06 : ディレクトリー名をbase_libより取得する仕様に変更
     
    */

class Upload_lib
{
    // SRCパス
//    const SRC_DIR = "src";
    const SRC_DIR = Base_lib::SRC_DIR;
    // 一時的SRCパス
//    const SRC_TEMP_DIR = "tmp";
    const SRC_TEMP_DIR = Base_lib::SRC_TEMP_DIR;
    // リサイズ
    const RESIZE_WIDTH = 720;
    const RESIZE_HEIGHT = 720;
    // 保存ディレクトリー
    // ノーイメージファイル名（1ドットgif画像）
    const NO_IMAGE_FILE = "noimage";
    // ディレクトリ作成時のパーミッション
    const PERMISSION_DIR = 0755;
    // ID生成用文字数
    const CREATE_ID_STRNUM = 10;
    // ファイル権限
    const CREATE_FILE_AUTH = 0666;
    
    const WEB_DIRECTORY_SEPARATOR = "/";
    // 画像クラス
    const CLASS_UPLOAD_IMG = 'upload_img';      // アップロード画像クラス
    // 閉じるボタン
    const NAME_CLOSE_BTN = 'close.svg';         // 画像名
    const CLASS_CLOSE_BTN = 'img_close_btn';    // クラス
    // 各クラスMIME一覧メソッド名
    const METHOD_NAME_IMG_TYPE = 'GetFileTypeImgList';
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct ()
    {
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名 : GetSrcPath
        概　要 : アップロードディレクトリを取得
    */
    function GetSrcPath ( $file = '' )
    {
        return realpath ( APPPATH . "../" . self::SRC_DIR ) . ( $file != "" ? DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名 : GetSrcPathVague
        概　要 : アップロードディレクトリを取得
    */
    function GetSrcPathVague ( $file = '' )
    {
        // 対象ファイルを取得（前方一致）
        $targetList = glob( $this->GetSrcPath() . DIRECTORY_SEPARATOR . $file . "*" );
        
        return $targetList[0];
    }
    /*====================================================================
        関数名 : GetSrcTempPath
        概　要 : アップロード仮登録用ディレクトリを取得
    */
    function GetSrcTempPath ( $file = '' )
    {
        return $this->GetSrcPath () . DIRECTORY_SEPARATOR . self::SRC_TEMP_DIR  . ( $file != "" ? DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名 : GetSrcWebPath
        概　要 : アップロードディレクトリを取得
    */
    function GetSrcWebPath ( $file = '' )
    {
        return D_ROOT . self::SRC_DIR . ( $file != "" ? self::WEB_DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名 : GetSrcWebPathVague
        概　要 : アップロードディレクトリを取得
    */
    function GetSrcWebPathVague ( $file = '' )
    {
        // 対象ファイルを取得（前方一致）
        $targetList = glob( $this->GetSrcPath() . DIRECTORY_SEPARATOR . $file . "*" );
        // 正しいパスを取得
        $targetFile = dirname ( $file ) . DIRECTORY_SEPARATOR . basename ( $targetList[0] );
        
        return D_ROOT . self::SRC_DIR . ( $targetFile != "" ? self::WEB_DIRECTORY_SEPARATOR . $targetFile : "" );
    }
    /*====================================================================
        関数名 : GetSrcWebTempPath
        概　要 : アップロード仮登録用ディレクトリを取得
    */
    function SrcWebTempPath ( $file = '' )
    {
        return $this->GetSrcWebPath () . self::WEB_DIRECTORY_SEPARATOR . self::SRC_TEMP_DIR  . ( $file != "" ? self::WEB_DIRECTORY_SEPARATOR . $file : "" );
    }
    /*====================================================================
        関数名 : GetCloseBtnTag
        概　要 : 閉じるボタンをタグ形式で返す
    */
    function GetCloseBtnTag ()
    {
        // 閉じるボタンパスをセット
        $srcPath = D_ROOT . Base_lib::IMG_DIR . Base_lib::WEB_DIR_SEPARATOR . self::NAME_CLOSE_BTN;
        // タグ形式で返す
        return '<img src="' . $srcPath .'" class="' . self::CLASS_CLOSE_BTN . '">';
    }
    /*====================================================================
        関数名 : DoUpload
        概　要 : 画像アップロード処理
        引　数 : $name：form名
                 $configData：設定情報配列
    */
    function DoUpload ( $name, $configData = array () )
    {
        // 設定情報引数にアップロード先がセットされている場合
        if ( isset ( $configData['upload_path'] ) ) {
            // ディレクトリが存在しない場合
            if ( ! file_exists ( $configData['upload_path'] ) ) {
                mkdir ( $configData['upload_path'] );
//              chmod ($uploadPath, self::PERMISSION_DIR);
            }
        }
        // アップロード - 設定基本情報を取得
        $configUpload = $this->ConfigBaseData ();
        // アップロード - 設定追加情報をセット
        if ( is_array ( $configData ) ) {
            foreach ( $configData AS $key => $val ) {
                if ( $val != '' ) {
                    $configUpload[$key] = $val;
                }
            }
        }
        // アップロードクラス初期化
        $this->CI->upload->initialize( $configUpload );
        // アップロードライブラリー読込み
//        $this->load->library("upload", $configUpload);
        
        // アップロード処理の実行
        if ( ! $this->CI->upload->do_upload( $name ) ) {
            // エラー時、エラー内容をセット
            $returnVal = array( 'error' => $this->CI->upload->display_errors() );
        }
        // アップロード成功時
        else {
            // アップロード情報をセット
            $returnVal = $this->CI->upload->data();
            // 拡張子を抜いたファイル名をセット
            $changeName = $returnVal['file_path'] . $returnVal['raw_name'];
            // 対象ファイルをリネーム（拡張子なし）
            rename ( $returnVal['full_path'], $changeName );
            // アップロードファイルのタイプが画像用MIME一覧に含まれる場合
            if ( in_array ( $returnVal['file_type'], $this->GetFileTypeImgList () ) ) {
                // 画像操作 - 設定情報
                $configImg = array (
                    "image_library" => "gd2",          // 画像操作ライブラリーを指定
                    "source_image" => $changeName,     // 処理元ファイル
                    "maintain_ratio" => TRUE,          // 縦横比の維持
                    "create_thumb" => FALSE,           // サムネイルの作成有無
                    "width" => self::RESIZE_WIDTH,     // 横幅
                    "height" => self::RESIZE_HEIGHT,   // 縦幅
                );
                // 画像操作クラス初期化
                $this->CI->image_lib->initialize( $configImg );
                // 画像操作ライブラリー読込み
    //            $this->load->library('image_lib', $configImg);
                // リサイズ処理の実行
                if ( ! $this->CI->image_lib->resize() )
                {
                    // エラー時、エラー内容をセット
                    $returnVal = array( 'error' => $this->CI->image_lib->display_errors() );
                }
            }
        }
        return $returnVal;
    }
    /*====================================================================
        関数名 : ConfigBaseData
        概　要 : 設定基本情報ををセット
    */
    function ConfigBaseData ()
    {
        $returnVal = array (
            "allowed_types" => "jpg|jpeg|gif|png", // ファイルのアップロード制限
            "overwrite" => TRUE,                   // 上書き設定
            "xss_clean" => TRUE,                   // XSSのフィルタリング
        );
        return $returnVal;
    }
    /*====================================================================
        関数名 : UploadFileTemp
        概　要 : 仮登録用ファイルを書込み
        引　数 : $imgName：画像ファイル名
                 $editConfigData : width：横幅、height：縦幅、create_thumb：サムネイル作成無、thumb_marker：サムネイルprefix
    */
    function UploadFileTemp ( $imgName = "", $editConfigData = array () )
    {
        // 返値を初期化
        $returnVal = false;
        // ファイルがアップロードされている場合
        if ( isset ( $_FILES[$imgName] ) && $_FILES[$imgName]['error'] == 0 ) {
            // ファイル名を生成
            $fileName = $this->CreateTempFileName ();
            // 仮登録用ファイルパスをセット
            $filePath = self::SRC_TEMP_DIR . self::WEB_DIRECTORY_SEPARATOR . $fileName;
            // コピー処理
            $flgCopy = copy ( $_FILES[$imgName]['tmp_name'], $this->GetSrcPath ( $filePath ) );
            if ( $flgCopy ) {
                // コピー先のファイル権限を変更
                chmod ( $this->GetSrcPath ( $filePath ), self::CREATE_FILE_AUTH );
                // 返値を更新
                $returnVal = $this->GetSrcWebPath ( $filePath );
                // 画像更新情報がセット
                if ( count ( $editConfigData ) > 0 ) {
                    // 画像更新処理
                    if ( ! $this->EditImg ( $this->GetSrcPath ( $filePath ), $editConfigData ) ) $returnVal = false;
                }
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : UploadFileMain
        概　要 : 本登録用ファイルを書込み
        引　数 : $tempFile：仮登録用ファイル, $tagetFile : 本登録用ファイル
    */
    function UploadFileMain ( $tempFile, $tagetFile = "" )
    {
        // 返値を初期化
        $returnVal = false;
        
        // 仮登録用ファイルの修正
        $tempFileArray = explode ( self::WEB_DIRECTORY_SEPARATOR, $tempFile );
        if ( count ( $tempFileArray ) > 0 ) {
            $tempFile  = $this->GetSrcTempPath( $tempFileArray[ max( array_keys ( $tempFileArray ) ) ] );
        }
        // ファイルが移動元に存在する場合
        if ( file_exists ( $tempFile ) ) {
            // アップロード先ファイル名をセット
            $uploadFile = $this->GetSrcPath () . DIRECTORY_SEPARATOR . $tagetFile;
            
            // 移動処理
            $renameFlg = rename ( $tempFile, $uploadFile );
            if ( $renameFlg ) {
                // 移動先のファイル権限を変更
                chmod ( $uploadFile, self::CREATE_FILE_AUTH );
                // 返値をセット
                $returnVal = true;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : UploadFileTmb
        概　要 : サムネイル用ファイルを書込み
        引　数 : $tagetFile : 対象画像ファイル
                 $tmbFile : サムネイル画像ファイル
                 $resizeWidth : リサイズ情報（横幅）
                 $resizeHeight : リサイズ情報（縦幅）
    */
    function UploadFileTmb ( $tagetFile, $tmbFile, $resizeWidth, $resizeHeight )
    {
        // 返値を初期化
        $returnVal = false;
        
        // 仮登録用ファイルの修正
        $tempFileArray = explode ( self::WEB_DIRECTORY_SEPARATOR, $tempFile );
        if ( count ( $tempFileArray ) > 0 ) {
            $tempFile  = $this->GetSrcTempPath( $tempFileArray[ max( array_keys ( $tempFileArray ) ) ] );
        }
        // 対象ファイルパスをセット
        $tagetPath = $this->GetSrcPath ( $tagetFile );
        // 対象ファイルパスが存在する場合
        if ( file_exists ( $tagetPath ) ) {
            // サムネイルファイルパスをセット
            $tmbPath = $this->GetSrcPath ( $tmbFile );
            // コピー処理
            $flgCopy = copy ( $tagetPath, $tmbPath );
            if ( $flgCopy ) {
                // リサイズ処理保存処理
                $returnVal = $this->ResizeImg ( $tagetPath, $tmbPath, $resizeWidth, $resizeHeight );
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : CreateTempFileName
        概　要 : 仮登録用ファイル名生成
    */
    public function CreateTempFileName ()
    {
        // 文字列用ヘルパー関数
        $this->CI->load->helper('string');
        do {
            // ファイル名生成
            $file = random_string('alnum', self::CREATE_ID_STRNUM);
        }
        // ファイル名が存在する場合、繰り返す
        while ( $this->TempFileExists ( $file ) );
        
        return $file;
    }
    /*====================================================================
        関数名 : ResizeImg
        概　要 : 指定された画像を縦横比を維持しながら伸縮し、JPEG画像として出力
        引　数 : $basePath : 対象元ファイルパス
                 $targetPath : リサイズ後ファイルパス
                 $resizeWidth : リサイズサイズ（横幅）
                 $resizeHeight : リサイズサイズ（縦幅）
    */
    function ResizeImg ( $basePath, $targetPath, $resizeWidth, $resizeHeight )
    {
        // 処理対象画像の情報を取得
        $img_info = @getimagesize ( $basePath );
        
        if ( ! $img_info ) {
            // エラー値を返す
            return false;
        }
        // 画像サイズの初期値をセット
        $default_target_w = $resizeWidth;
        $default_target_h = $resizeHeight;
        
        // ターゲットサイズの縦横比を求める
        $target_ratio = $resizeHeight / $resizeWidth;
        // 元画像の縦横比を求める
        $source_ratio = $img_info[1] / $img_info[0];
        
        // 指定された矩形よりも小さい場合、リサイズは行わない
        if ( $img_info[0] > $resizeWidth || $img_info[1] > $resizeHeight ) {
            // 変換後の画像サイズを計算
            if ( $target_ratio > $source_ratio ) {
                // 高さを求める
                $resizeHeight = floor ( $img_info[1] * ( $resizeWidth / $img_info[0] ) );
            }
            else {
                // 幅を求める
                $resizeWidth = floor ( $img_info[0] * ( $resizeHeight / $img_info[1] ) );
            }
            $resize = true;
        }
        else {
            $resizeWidth = $img_info[0];
            $resizeHeight = $img_info[1];
            $resize = false;
        }
        // 元画像からコピー元イメージを作成
        switch ( $img_info[2] ) {
            case 1:
                $src_im = @imagecreatefromgif ( $basePath );
                break;
            case 2:
                $src_im = @imagecreatefromjpeg ( $basePath );
                break;
            case 3:
                $src_im = @imagecreatefrompng ( $basePath );
                break;
            default:
                $src_im = false;
        }
        if ( !$src_im ) {
            // エラー値を返す
            return false;
        }
        
        if ( $resize ) {
            // ターゲットイメージの作成
            $dst_im = @imagecreatetruecolor ( $resizeWidth, $resizeHeight );
            // 画像を伸縮する
            $r = @imagecopyresampled ( $dst_im, $src_im, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $img_info[0], $img_info[1] );
            if ( ! $r ) {
                // エラー値を返す
                return false;
            }
        }
        else {
            $dst_im = $src_im;
        }
        // 背景の画像を作成
        $back_image = imagecreatetruecolor( $default_target_w, $default_target_h );
        // 背景色をセット
        $white_color = imagecolorallocate($back_image, 255, 255, 255);
        // 背景画像を作成
        ImageFilledRectangle ( $back_image, 0, 0, $default_target_w, $default_target_h, $white_color );
        // 画像合成
        ImageCopy( $back_image, $dst_im, ( $default_target_w - $resizeWidth ) / 2, ( $default_target_h - $resizeHeight ) / 2, 0, 0, $resizeWidth, $resizeHeight );
        
        // 変数名を元に戻す
        $dst_im = $back_image;
        
        if ( $targetPath == "" ) {
            header('Content-type: image/jpeg');
        }
        // jpegイメージとして出力
        @imagejpeg ($dst_im, $targetPath, 100);
        
        @imagedestroy($back_image);
        @imagedestroy($src_im);
        @imagedestroy($dst_im);
        
        return true;
    }
    /*====================================================================
        関数名 : FileExists
        概　要 : 登録用ファイルの存在確認
    */
    public function FileExists ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( file_exists ( $this->GetSrcPath() . DIRECTORY_SEPARATOR . $file  ) ) {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : FileExistsVague
        概　要 : 登録用ファイルの存在確認（前方一致）
    */
    public function FileExistsVague ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        // 対象ファイルの存在を確認（前方一致）
        $targetList = glob( $this->GetSrcPath() . DIRECTORY_SEPARATOR . $file . "*" );
        if (
             count ( $targetList ) > 0 &&
             $targetList != false
        ) {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : EditImg
        概　要 : 画像をリサイズして再登録
        引　数 : $filePath : 画像ファイルパス
                 $configData : width：横幅、height：縦幅、create_thumb：サムネイル作成無、thumb_marker：サムネイルprefix
    */
    function EditImg ( $filePath, $configData )
    {
        // 追加情報をセット
        $configData["source_image"] = $filePath;    // 処理元ファイル
        $configData["maintain_ratio"] = TRUE;       // 縦横比の維持
        // 画像ライブラリの初期化
        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize( $configData );
        //リサイズ実行
        $returnVal = $this->CI->image_lib->resize();
        // 値を初期化
        $this->CI->image_lib->clear();
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : FileDelete
        概　要 : ファイルの削除
    */
    public function FileDelete ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( unlink ( $this->GetSrcPath() . DIRECTORY_SEPARATOR . $file  ) ) {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : TempFileExists
        概　要 : 仮登録用ファイルの存在確認
    */
    public function TempFileExists ( $file )
    {
        // 返値を初期化
        $returnVal = false;
        
        if ( file_exists ( $this->GetSrcTempPath() . DIRECTORY_SEPARATOR . $file  ) ) {
            $returnVal = true;
        }
        
        return $returnVal;
    }
    //====================================================================
    //  関数名 : ValidImage
    //  概　要 : 画像の形式確認を返す
    //  引　数 : $formName : フォーム名
    //           $extList : 登録可能拡張子（配列）
    //           $srcFilePath : 保存ファイルパス
    function ValidImage ( $formName, $extList = array (), $srcFilePath = '' )
    {
        $returnVal = '';
        // ファイルが未セット、かつ、登録済みのファイルが存在しない
        if (
            ( ( ! isset ( $_FILES[$formName] ) ) || $_FILES[$formName]['size'] == 0 ) &&
            ! $this->CI->session_lib->GetSessionVal ( $formName ) &&
            ( ! $srcFilePath || ( $srcFilePath && ! $this->FileExists ( $srcFilePath ) ) )
        ) {
            $returnVal = 'ファイルを選択してください';
        }
        // ファイルの拡張子
        else if ( isset ( $_FILES[$formName] ) && $_FILES[$formName]['size'] != 0 ) {
           // ファイルタイプ
           $pathinfo = pathinfo ( $_FILES[$formName]['name'] );
            if (
                ! isset ( $pathinfo['extension'] ) ||
                ! in_array ( $pathinfo['extension'], $extList )
            ) {
                $returnVal = '無効な拡張子です';
            }
        }
        
        // エラー文言がセット済みの場合
        if ( $returnVal != '' ) {
            // エラー専用タグで囲う
            $returnVal = Base_lib::VALID_STR_BEFORE . $returnVal . Base_lib::VALID_STR_AFTER;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名 : GetFileTypeImgList
        概　要 : 画像用MIMEファイルタイプ一覧を取得
    */
    public function GetFileTypeImgList ()
    {
        // 画像用MIMEファイルタイプ一覧をセット
        $returnVal = array (
            'image/jpeg',
            'image/png',
            'image/gif',
        );
        
        return $returnVal;
    }
}
?>