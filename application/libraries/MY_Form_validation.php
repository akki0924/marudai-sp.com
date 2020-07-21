<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： Form_validationライブラリ拡張バリデーションライブラリー
    ■概　要： バリデーションを独自設定関数群
    ■更新日： 2018/02/06
    ■担　当： crew.miwa

    ■更新履歴：
     2018/02/06: 作成開始
     
    */

class MY_Form_validation extends CI_Form_validation
{
    // メンバー変数
    protected $CI;              // スーパーオブジェクト割当用
    // ログイン対象
    const LOGIN_USER_KEY = "user";
    const LOGIN_ADMIN_KEY = "admin";
    // 分割用文字列
    const SPLIT_STR = "|";
    //====================================================================
    //  コントラクト
    //
    public function __construct( $params = array () )
    {
        $this->CI =& get_instance();
    }
    //====================================================================
    //  関数名 : ValidLoginUser
    //  概　要 : メールアドレスとパスワードによりログイン情報の有無を返す
    //  引　数 : $password : パスワード
    //           $email : ID（メールアドレス）
    function ValidLoginUser ( $password, $email )
    {
        // ログイン変数をセット
        $loginTarget['key'] = self::LOGIN_USER_KEY;
        // ライブラリー読込み
        $this->CI->load->library( 'login_lib', $loginTarget );
        $this->CI->load->library( Base_lib::MASTER_DIR . '/user_lib' );
        // エラー
        if ( $this->CI->user_lib->GetRegistTypeToEmail ( $email, true ) > User_lib::ID_REGIST_TYPE_REGULAR ) {
            $this->set_message( 'ValidLoginUser',  'ゲストユーザーでのご購入は出来ません。ユーザー登録をお願いします。' );
            return false;
        }
        else if( $this->CI->login_lib->LoginAction ( $email, $password ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidLoginUser',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidLoginAdmin
    //  概　要 : アカウントとパスワードによりログイン情報の有無を返す
    //  引　数 : $password : パスワード
    //           $account : アカウント
    function ValidLoginAdmin ( $password, $account )
    {
        // ログイン変数をセット
        $loginTarget['key'] = self::LOGIN_ADMIN_KEY;
        // ライブラリー読込み
        $this->CI->load->library( 'login_lib', $loginTarget );
        if( $this->CI->login_lib->LoginAction ( $account, $password ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidLoginAdmin',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidKatakana
    //  概　要 : カタカナ入力かどうか
    //  引　数 : $str : 文字列
    function ValidKatakana ( $str )
    {
        if ( preg_match( "/^[ァ-ヶー]+$/u", $str ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidKatakana',  'カタカナで入力してください' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidDate
    //  概　要 : 日付情報が正しいかどうか
    //  引　数 : $tmpVal : 使用しない
    //           $targetDate : 対象日付
    function ValidDate ( $tmpVal, $targetDate )
    {
        // 数字のみ
        if ( is_numeric ( $targetDate ) ) {
            $year = substr ( $targetDate, 0, 4 );
            $month = substr ( $targetDate, 4, 2 );
            $day = substr ( $targetDate, 6, 2 );
        }
        // スラッシュ、ドット、ハイフンで分割
        else if (
            strpos( $targetDate, '/' ) !== false ||
            strpos( $targetDate, '.' ) !== false ||
            strpos( $targetDate, '-' ) !== false
        ) {
            list( $year, $month, $day ) = preg_split ( '/[\/\.\-]/', $targetDate );
        }
        
        if (
            isset ( $year ) &&
            isset ( $month ) &&
            isset ( $day ) &&
            checkdate ( $month, $day, $year )
        ) {
            return true;
        }
        else {
            $this->set_message( 'ValidDate',  '入力内容をご確認下さい。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemId
    //  概　要 : 対象商品IDが存在するかを返す
    //  引　数 : $itemId : 商品ID
    function ValidItemId ( $itemId, $public = false )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        
        if( $this->CI->item_lib->IdExists ( $itemId, $public ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemId',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemDisplayType
    //  概　要 : 対象商品掲載カテゴリーの登録有無を返す
    //  引　数 : $displayType : 商品掲載カテゴリー
    function ValidItemDisplayType ( $displayType )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        
        if( $this->CI->item_lib->DisplayTypeExists ( $displayType ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemDisplayType',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemOrderCode
    //  概　要 : 対象商品ご注文番号の他の登録有無を返す
    //  引　数 : $orderCode : ご注文番号
    //           $id : 商品ID
    function ValidItemOrderCode ( $orderCode, $id = '' )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        if( ! $this->CI->item_lib->OrderCodeSameExists ( $orderCode, $id, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemOrderCode',  '既に登録されています。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemStatus
    //  概　要 : 対象商品ステータスの登録有無を返す
    //  引　数 : $status : 商品ステータス
    function ValidItemStatus ( $status )
    {
        if( $this->CI->status_lib->SelectExists ( 'KOUKAI_HIKOUKAI', $status ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemStatus',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemCategory
    //  概　要 : 対象商品カテゴリー対象選択有無を返す
    //  引　数 : $category : 選択カテゴリー情報（未使用）
    //           $category_list : 選択カテゴリーリストと、サブカテゴリーリストの結合文字列
/*
    function ValidItemCategory ( $category, $category_list = '' )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/category_lib' );
        
        // 返り値用の値をセット
        $returnVal = true;
        
        // 引数を再構成
        // カテゴリー結合情報を分割
        $category_list = ( isset ( $category_list ) ? explode ( Base_lib::VALID_SEPARATE_STR, $category_list ) : '' );
        // カテゴリー情報を配列化
        $category = ( isset ( $category_list[0] ) ? explode ( ',', $category_list[0] ) : '' );
        // サブカテゴリー情報を配列化
        $sub_category = ( isset ( $category_list[1] ) ? explode ( ',', $category_list[1] ) : '' );
        
        // カテゴリー情報が配列の場合
        if ( is_array ( $category ) ) {
            foreach ( $category AS $key => $val ) {
                // カテゴリー、サブカテゴリーどちらかに値がセットされていない
                if ( ! isset ( $category[$key] ) || ! isset ( $sub_category[$key] ) ) {
                    $this->set_message( 'ValidItemCategory',  '選択されていない項目があります。' );
                    $returnVal = false;
                    break;
                }
                else {
                    // 選択カテゴリーが登録されていない
                    if ( ! $this->CI->category_lib->IdExists( $category[$key], true ) ) {
                        $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                        $returnVal = false;
                        break;
                    }
                    else {
                        // カテゴリーIDとサブカテゴリー親IDが一致していない
                        if ( $category[$key] != $this->CI->category_lib->GetParentId ( $sub_category[$key], true ) ) {
                            $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                            $returnVal = false;
                            break;
                        }
                    }
                }
            }
        }
        else {
            // カテゴリー、サブカテゴリーどちらかに値がセットされていない
            if ( ! isset ( $category ) || ! isset ( $sub_category ) ) {
                $this->set_message( 'ValidItemCategory',  '選択されていない項目があります。' );
                $returnVal = false;
            }
            else {
                // 選択カテゴリーが登録されていない
                if ( ! $this->CI->category_lib->IdExists( $category, true ) ) {
                    $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                    $returnVal = false;
                }
                else {
                    // カテゴリーIDとサブカテゴリー親IDが一致していない
                    if ( $category != $this->CI->category_lib->GetParentId ( $sub_category, true ) ) {
                        $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                        $returnVal = false;
                    }
                    // 関連IDを取得
                    $relation_id = $this->CI->category_lib->GetRelationId ( $category, true );
                    // 関連IDとサブカテゴリの親IDが一致しているか
                    if ( $relation_id == $this->CI->category_lib->GetParentId ( $sub_category, true ) ) {
                        $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                    }
                }
            }
        }
        
        return $returnVal;
    }
*/
    function ValidItemCategory ( $category, $sub_category = '' )
    {
        // 返り値用の値をセット
        $returnVal = true;
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/category_lib' );
        
        // カテゴリー、サブカテゴリーどちらかに値がセットされていない
        if ( ! isset ( $category ) || ! isset ( $sub_category ) ) {
            $this->set_message( 'ValidItemCategory',  '選択されていない項目があります。' );
            $returnVal = false;
        }
        else {
            // 選択カテゴリーが登録されていない
            if ( ! $this->CI->category_lib->IdExists( $category, true ) ) {
                $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                $returnVal = false;
            }
            else {
                // カテゴリーIDとサブカテゴリー親IDが一致していない
                if ( $category != $this->CI->category_lib->GetParentId ( $sub_category, true ) ) {
                    $this->set_message( 'ValidItemCategory',  '選択内容をご確認ください。' );
                    $returnVal = false;
                }
            }
        }
        
        return $returnVal;
    }
    //====================================================================
    //  関数名 : ValidItemOptionPattern
    //  概　要 : 対象商品オプションパターンの登録有無を返す
    //  引　数 : $status : 商品オプションパターン
    function ValidItemOptionPattern ( $patternId )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_option_lib' );
        if( $this->CI->item_option_lib->PatterIdExists ( $patternId ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemOptionPattern',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemNaire
    //  概　要 : 対象商品ステータスの登録有無を返す
    //  引　数 : $id : 名入れID
    function ValidItemNaire ( $id )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/trophy_lib' );
        if( $this->CI->trophy_lib->NaireExists ( $id ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidItemNaire',  '選択内容をご確認ください。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidAdminAccount
    //  概　要 : 管理者アカウントの他の登録有無を返す
    //  引　数 : $account : アカウント
    function ValidAdminAccount ( $account, $id = '' )
    {
        if( ! $this->CI->admin_lib->AccountSameExists ( $account, $id, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidAdminAccount',  '既に登録されています。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidUserEmail
    //  概　要 : 対象ユーザーE-mailの他の登録有無を返す
    //  引　数 : $email : E-mail
    //           $id : ユーザーID
    function ValidUserEmail ( $email, $id = '' )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/user_lib' );
        if( ! $this->CI->user_lib->EmaileSameExists ( $email, $id, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidUserEmail',  '既に登録されています。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidFeatureOrderCode
    //  概　要 : 対象特集ページのご注文番号の登録有無を返す
    //  引　数 : $orderCode : ご注文番号
    //           $id : 商品ID
    function ValidFeatureOrderCode ( $orderCode )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        if( $this->CI->item_lib->OrderCodeExists ( $orderCode, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidFeatureOrderCode',  '登録されていないご注文番号です。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidZip
    //  概　要 : 郵便番号の形式確認を返す
    //  引　数 : $zip1 : 郵便番号前半（または単独）
    //           $zip2 : 郵便番号後半
    function ValidZip ( $zip1, $zip2 = '' )
    {
        // 郵便番号（入力内容をまとめる）
        $zip = $zip1 . $zip2;
        // ハイフン削除処理
        $zip = str_replace ( array('-', 'ー', '―', '‐'), '', $zip );
        // ７桁の数字
        if(
            $zip1 != '' &&
            preg_match("/^[0-9]{7}$/", $zip)
        ) {
            return true;
        }
        else {
            $this->set_message( 'ValidZip',  '形式が間違っています。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidTel
    //  概　要 : 電話番号の形式確認を返す
    //  引　数 : $tel1 : 電話番号（１つ目）
    //           $tel2tel3 : 電話番号（２つ目以降）
    function ValidTel ( $tel1, $tel2tel3 = '' )
    {
        // エラーメッセージ用変数を初期化
        $errMsg = '';
        // 分割用文字列が存在する
        if ( strpos( $tel2tel3, Base_lib::VALID_SEPARATE_STR ) !== false ) {
            $tel2tel3List = explode ( Base_lib::VALID_SEPARATE_STR, $tel2tel3 );
            if (
                $tel1 != '' &&
                (
                    $tel2tel3List[0] == '' ||
                    $tel2tel3List[1] == ''
                )
            ) {
                $errMsg = '{field}は必須です';
            }
            else {
                // 電話番号（入力内容をまとめる）
                $tel = $tel1 . $tel2tel3List[0] . $tel2tel3List[1];
            }
        }
        else {
            $tel = $tel1 . $tel2tel3;
        }
        if ( isset ( $tel ) ) {
            // ハイフン削除処理
            $tel = str_replace ( array ('-', 'ー', '―', '‐'), '', $tel );
            // ７桁の数字
            if(
                $tel1 != '' &&
                ! preg_match( "/^[\d]{10,}$/", $tel )
             ) {
                $errMsg = '形式が間違っています';
            }
        }
        
        // エラー文言が未セット
        if ( $errMsg == '' ) {
            return true;
        }
        else {
            $this->set_message( 'ValidTel',  $errMsg );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidImage
    //  概　要 : 画像の形式確認を返す
    //  引　数 : $formName : フォーム名
    //           $srcFilePath : 保存ファイルパス
    function ValidImage ( $formName, $srcFilePath = '' )
    {
        $returnVal = true;
        // ファイルが未セット、かつ、登録済みのファイルが存在しない
        if (
            ( ( ! isset ( $_FILES[$formName] ) ) || $_FILES[$formName]['size'] == 0 ) &&
            ! $this->session_lib->GetSessionVal ( $formName ) &&
            ( ! $srcFilePath || ( $srcFilePath && ! $this->CI->upload_lib->FileExists ( $srcFilePath ) ) )
        ) {
            $this->form_validation->set_message( 'ValidImage', 'ファイルを選択してください' );
            $returnVal = false;
        }
        // ファイルの拡張子
        else if ( isset ( $_FILES[$formName] ) && $_FILES[$formName]['size'] != 0 ) {
            // ライブラリー名の確認
            if ( $libName ) {
                // 対象ライブラリーを読込み
                $this->CI->load->library ( ucfirst ( $libName ) );
                // 変数に代入
                $targetLib = $this->CI->{$libName};
            }
            // ファイルタイプ
            if( ! in_array ( $_FILES[$formName]['type'], $this->CI->upload_lib->GetFileTypeImgList () ) ) {
                $this->form_validation->set_message( 'ValidImage', '無効なファイルタイプです' );
                $returnVal = false;
            }
        }
        return $returnVal;
    }
    //====================================================================
    //  関数名 : ValidImageCheck
    //  概　要 : 画像の形式確認を返す
    //  引　数 : $formName : フォーム名
    //           $srcFilePath : 保存ファイルパス
    function ValidImageCheck ( $check, $formName )
    {
        $returnVal = true;
        
        $srcFilePath = '';
        
        // ファイルが未セット、かつ、登録済みのファイルが存在しない
        if (
            ( ( ! isset ( $_FILES[$formName] ) ) || $_FILES[$formName]['size'] == 0 ) &&
            ! $this->session_lib->GetSessionVal ( $formName ) &&
            ( ! $srcFilePath || ( $srcFilePath && ! $this->CI->upload_lib->FileExists ( $srcFilePath ) ) )
        ) {
            $this->form_validation->set_message( 'ValidImage', 'ファイルを選択してください' );
            $returnVal = false;
        }
        // ファイルの拡張子
        else if ( isset ( $_FILES[$formName] ) && $_FILES[$formName]['size'] != 0 ) {
            // ライブラリー名の確認
            if ( $libName ) {
                // 対象ライブラリーを読込み
                $this->CI->load->library ( ucfirst ( $libName ) );
                // 変数に代入
                $targetLib = $this->CI->{$libName};
            }
            // ファイルタイプ
            if( ! in_array ( $_FILES[$formName]['type'], $this->CI->upload_lib->GetFileTypeImgList () ) ) {
                $this->form_validation->set_message( 'ValidImage', '無効なファイルタイプです' );
                $returnVal = false;
            }
        }
        return $returnVal;
    }
    //====================================================================
    //  関数名 : ValidWebcatalogPagearea
    //  概　要 : 対象WEBカタログページのページ範囲の入力形式の是非を返す
    //  引　数 : $pageArea : ページ範囲
    function ValidWebcatalogPagearea ( $pageArea )
    {
        if ( preg_match ( '/^\d+-\d+$/', $pageArea ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidWebcatalogPagearea',  '形式が正しくありません' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidPasswordEmail
    //  概　要 : 対象ユーザーE-mailの他の登録有無を返す
    //  引　数 : $email : E-mail
    function ValidPasswordEmail ( $email )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/user_lib' );
        if( $this->CI->user_lib->GetEmailToIDforRegular ( $email, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidPasswordEmail',  '登録されていないメールアドレスです' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidPasswordPassQuestion
    //  概　要 : パスワード再発行のご質問は登録されたものか返す
    //  引　数 : $pass_question : パスワード再発行のご質問
    //           $id : ユーザーID
    function ValidPasswordPassQuestion ( $pass_question, $id = '' )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/user_lib' );
        if( $pass_question == $this->CI->user_lib->GetPassQuestion ( $id, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidPasswordPassQuestion',  '登録内容をご確認ください' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidPasswordPassAnswer
    //  概　要 : パスワード再発行のご質問の答えは登録されたものか返す
    //  引　数 : $pass_answer : ご質問の答え
    //           $id : ユーザーID
    function ValidPasswordPassAnswer ( $pass_answer, $id = '' )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/user_lib' );
        if( $pass_answer == $this->CI->user_lib->GetPassAnswer ( $id, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidPasswordPassAnswer',  '登録内容をご確認ください' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : validWebcatalog
    //  概　要 : カタログ見ながら注文ページにカートに入れるかを返す
    //  引　数 : $num : 注文セット数
    //           $id : 商品ID
    function validWebcatalog ( $num, $id )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        
        if ( ! preg_match( '/^[0-9]+$/', $num ) || $num === '' ) {
            $this->set_message( 'validWebcatalog',  '半角数字を入力してください' );
            return false;
        }
        else if ( $num <= 0 ) {
            $this->set_message( 'validWebcatalog',  '1以上の数値を入力してください' );
            return false;
        }
        else if (
            $id != '' &&
            $num > 0 &&
            ! $this->CI->item_lib->StockNumExists ( $id, $num, true )
        ) {
            $this->set_message( 'validWebcatalog',  '在庫数がありません' );
            return false;
        }
        else {
            return true;
        }
    }
    //====================================================================
    //  関数名 : validHinban
    //  概　要 : 品番注文ページにカートに入れるかを返す
    //  引　数 : $orderCode : ご注文番号
    //           $num : 注文セット数
    function validHinban ( $orderCode, $num = 0 )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        // 商品IDを取得
        $itemId = $this->CI->item_lib->GetOrderCodeToId ( $orderCode, true );
        
        if ( $itemId == '' ) {
            $this->set_message( 'validHinban',  'ご購入できない注文番号です' );
            return false;
        }
        else if ( ! preg_match( '/^[0-9]+$/', $num ) || $num === '' ) {
            $this->set_message( 'validHinban',  '半角数字を入力してください' );
            return false;
        }
        else if ( $num <= 0 ) {
            $this->set_message( 'validHinban',  '1以上の数値を入力してください' );
            return false;
        }
        else if (
            $itemId != '' &&
            $num > 0 &&
            ! $this->CI->item_lib->StockNumExists ( $itemId, $num, true )
        ) {
            $this->set_message( 'validHinban',  '在庫数がありません' );
            return false;
        }
        else {
            return true;
        }
    }
    //====================================================================
    //  関数名 : validOrderEditAdd
    //  概　要 : 品番注文ページにカートに入れるかを返す
    //  引　数 : $orderCode : ご注文番号
    //           $num : 注文セット数
    function validOrderEditAdd ( $orderCode, $num = 0 )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        // 商品IDを取得
        $itemId = $this->CI->item_lib->GetOrderCodeToId ( $orderCode, true );
        
        if ( $itemId == '' ) {
            $this->set_message( 'validOrderEditAdd',  'ご購入できない注文番号です' );
            return false;
        }
        else if ( ! preg_match( '/^[0-9]+$/', $num ) || $num === '' ) {
            $this->set_message( 'validHinban',  '半角数字を入力してください' );
            return false;
        }
        else if ( $num <= 0 ) {
            $this->set_message( 'validOrderEditAdd',  '1以上の数値を入力してください' );
            return false;
        }
        else {
            return true;
        }
    }
    //====================================================================
    //  関数名 : ValidReceiptPermissionCheck
    //  概　要 : 申請番号とメールアドレスから電子領収書発行確認できるかの有無を返す
    //  引　数 : $main_id : 申請番号
    //           $email : 申請メールアドレス
    function ValidReceiptPermissionCheck ( $main_id, $email )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/receipt_lib' );
        if( $this->CI->receipt_lib->MainIdEmailExists ( Receipt_lib::ID_TOP_STR . $main_id, $email, true ) ) {
            return true;
        }
        else {
            $this->set_message( 'ValidReceiptPermissionCheck',  '入力された情報は登録されておりません。' );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidCartNumCheck
    //  概　要 : カート注文時の購入の可否を返す
    //  引　数 : $num : 商品数
    //           $item_id : 商品ID
    function ValidCartNumCheck ( $num, $item_id )
    {
        // ライブラリー読込み
        $this->CI->load->library( Base_lib::MASTER_DIR . '/item_lib' );
        // エラーメッセージ用変数を初期化
        $errMsg = '';
        // 数量チェック
        if ( $num <= 0 ) {
            $errMsg = '入力内容をご確認下さい。';
        }
        // 公開／非公開
        else if ( ! $this->CI->item_lib->IdExists ( $item_id, true )  ) {
            $errMsg = '現在、購入が出来ません。';
        }
        // 在庫数
        else if ( ! $this->CI->item_lib->StockNumExists ( $item_id, $num, true ) ) {
            $errMsg = '在庫数がありません。';
        }
        // 名入れ
        else if (  ! $this->CI->item_lib->NamePrintCheck ( $item_id, $num, true ) ) {
            $errMsg = '在庫数がありません。';
        }
        // エラー文言が未セット
        if ( $errMsg == '' ) {
            return true;
        }
        else {
            $this->set_message( 'ValidCartNumCheck',  $errMsg );
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidError
    //  概　要 : 強制的にエラーを返す
    //  引　数 : $targetVal : 対象の値
    //           $errMsg : エラーメッセージ
    function ValidError ( $targetVal, $errMsg = '' )
    {
        $this->set_message( 'ValidError',  ( $errMsg != '' ? $errMsg : '内容をご確認ください' ) );
        return false;
    }
}
?>