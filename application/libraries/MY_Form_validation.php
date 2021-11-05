<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
    /*
    ■機　能： Form_validationライブラリ拡張バリデーションライブラリー
    ■概　要： バリデーションを独自設定関数群
    ■更新日： 2021/04/09
    ■担　当： crew.miwa

    ■更新履歴：
        2018/02/06 : 作成開始
        2021/04/09 : サイト用に関数追加
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
    public function __construct($params = array())
    {
        $this->CI =& get_instance();
    }
    //====================================================================
    //  関数名 : ValidLoginUser
    //  概　要 : メールアドレスとパスワードによりログイン情報の有無を返す
    //  引　数 : $password : パスワード
    //           $email : ID（メールアドレス）
    public function ValidLoginUser($password, $email)
    {
        // ログイン変数をセット
        $loginTarget['key'] = self::LOGIN_USER_KEY;
        // ライブラリー読込み
        $this->CI->load->library('login_lib', $loginTarget);
        $this->CI->load->library(Base_lib::MASTER_DIR . '/user_lib');
        // ログインOK
        if ($this->CI->login_lib->LoginAction($email, $password)) {
            return true;
        } else {
            $this->set_message('ValidLoginUser', '入力された情報は登録されておりません。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidLoginAdmin
    //  概　要 : アカウントとパスワードによりログイン情報の有無を返す
    //  引　数 : $password : パスワード
    //           $account : アカウント
    public function ValidLoginAdmin($password, $account)
    {
        // ログイン変数をセット
        $loginTarget['key'] = self::LOGIN_ADMIN_KEY;
        // ライブラリー読込み
        $this->CI->load->library('login_lib', $loginTarget);
        if ($this->CI->login_lib->LoginAction($account, $password)) {
            return true;
        } else {
            $this->set_message('ValidLoginAdmin', '入力された情報は登録されておりません。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidKatakana
    //  概　要 : カタカナ入力かどうか
    //  引　数 : $str : 文字列
    public function ValidKatakana($str)
    {
        if (preg_match("/^[ァ-ヶー]+$/u", $str)) {
            return true;
        } else {
            $this->set_message('ValidKatakana', 'カタカナで入力してください');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidDate
    //  概　要 : 日付情報が正しいかどうか
    //  引　数 : $tmpVal : 使用しない
    //           $targetDate : 対象日付
    public function ValidDate($tmpVal, $targetDate)
    {
        // 数字のみ
        if (is_numeric($targetDate)) {
            $year = substr($targetDate, 0, 4);
            $month = substr($targetDate, 4, 2);
            $day = substr($targetDate, 6, 2);
        }
        // スラッシュ、ドット、ハイフンで分割
        elseif (
            strpos($targetDate, '/') !== false ||
            strpos($targetDate, '.') !== false ||
            strpos($targetDate, '-') !== false
        ) {
            list($year, $month, $day) = preg_split('/[\/\.\-]/', $targetDate);
        }

        if (
            isset($year) &&
            isset($month) &&
            isset($day) &&
            checkdate($month, $day, $year)
        ) {
            return true;
        } else {
            $this->set_message('ValidDate', '入力内容をご確認下さい。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidDateAdult
    //  概　要 : 日付情報で成人かどうか
    //  引　数 : $tmpVal : 使用しない
    //           $targetDate : 対象日付
    public function ValidDateAdult($tmpVal, $targetDate)
    {
        // ライブラリー読込み
        $this->CI->load->library('date_lib');
        // 年齢を取得
        $age = $this->CI->date_lib->GetAge($targetDate);
        if ($age >= 20) {
            return true;
        } else {
            $this->set_message('ValidDateAdult', '未成年の応募はできません。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidRepeated
    //  概　要 : 重複チェック
    //  引　数 : $tmpVal : 使用しない
    //           $targetDate : 対象情報
    //              氏名（漢字）
    //              誕生日
    //              ユーザーID
    //              対象エリア
    //              対象日付（配列）
    public function ValidRepeated($tmpVal, $targetVal)
    {
        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/user_lib');
        $this->CI->load->library(Base_lib::MASTER_DIR . '/reserve_lib');
        $this->CI->load->library('date_lib');
        // 対象情報を各変数に分割
        list($name, $birth, $id, $areaId, $date) = preg_split('/[' . Base_lib::VALID_SEPARATE_STR . ']/', $targetVal);
        // 氏名（漢字）、誕生日から重複チェック
        $repeatedFlg = $this->CI->user_lib->NameBirthTelSameExists(
            $name,
            $birth,
            $id,
            User_lib::ID_STATUS_TEMP
        );
        // 成功
        if (! $repeatedFlg) {
            return true;
        }
        // 失敗
        else {
            // エラー文字列を初期化
            $errMsg = '';
            // IDが未セット
            if (!$id) {
                // 名前と誕生日からIDを再セット
                $id = $this->CI->user_lib->GetNameBirthToId($name, $birth, User_lib::ID_STATUS_TEMP);
            }
            // IDがセット
            if ($id) {
                // 対象エリアが指定席の場合
                if ($areaId == Reserve_lib::ID_AREA_SELECT) {
                    $dateArray = @explode(Reserve_lib::VALID_SEPARATE_STR_DATE, $date);
                    // 登録済みの日付一覧を取得
                    $dateList = $this->CI->reserve_lib->GetUserDateCheckList($dateArray, $name, $birth, Reserve_lib::ID_STATUS_TEMP);
                    for ($i = 0, $n = count($dateList); $i < $n; $i ++) {
                        // エラーメッセージが複数になる際、エラーメッセージ用のタグを挟む
                        $errMsg .= ($i > 0 ? '</div><div class="form_error">' : '');
                        $day = new DateTime($dateList[$i]['date']);
                        $dateDisp = $day->format('Y年n月j日');
                        $dateDisp .= '（' . $this->CI->date_lib->GetWeekListName($day->format('w')) . '）';
                        $errMsg .= '来場希望日：' . $dateDisp . 'は既に応募が完了しております。';
                    }
                }
            }
            // エラーメッセージが未セット
            if (!$errMsg) {
                return true;
            }
            // エラーメッセージセット
            else {
                $this->set_message('ValidRepeated', $errMsg);
                return false;
            }
        }
    }
    //====================================================================
    //  関数名 : ValidItemId
    //  概　要 : 対象商品IDが存在するかを返す
    //  引　数 : $itemId : 商品ID
    public function ValidItemId($itemId, $public = false)
    {
        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/item_lib');

        if ($this->CI->item_lib->IdExists($itemId, $public)) {
            return true;
        } else {
            $this->set_message('ValidItemId', '入力された情報は登録されておりません。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidItemStatus
    //  概　要 : 対象商品ステータスの登録有無を返す
    //  引　数 : $status : 商品ステータス
    public function ValidItemStatus($status)
    {
        if ($this->CI->status_lib->SelectExists('KOUKAI_HIKOUKAI', $status)) {
            return true;
        } else {
            $this->set_message('ValidItemStatus', '入力された情報は登録されておりません。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidAdminAccount
    //  概　要 : 管理者アカウントの他の登録有無を返す
    //  引　数 : $account : アカウント
    public function ValidAdminAccount($account, $id = '')
    {
        if (! $this->CI->admin_lib->AccountSameExists($account, $id, true)) {
            return true;
        } else {
            $this->set_message('ValidAdminAccount', '既に登録されています。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidUserEmail
    //  概　要 : 対象ユーザーE-mailの他の登録有無を返す
    //  引　数 : $email : E-mail
    //           $id : ユーザーID
    public function ValidUserEmail($email, $id = '')
    {
        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/user_lib');
        if (! $this->CI->user_lib->EmailSameExists($email, $id, true)) {
            return true;
        } else {
            $this->set_message('ValidUserEmail', '既に登録されています。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidZip
    //  概　要 : 郵便番号の形式確認を返す
    //  引　数 : $zip1 : 郵便番号前半（または単独）
    //           $zip2 : 郵便番号後半
    public function ValidZip($zip1, $zip2 = '')
    {
        // 郵便番号（入力内容をまとめる）
        $zip = $zip1 . $zip2;
        // ハイフン削除処理
        $zip = str_replace(array('-', 'ー', '―', '‐'), '', $zip);
        // ７桁の数字
        if (
            $zip1 != '' &&
            preg_match("/^[0-9]{7}$/", $zip)
        ) {
            return true;
        } else {
            $this->set_message('ValidZip', '形式が間違っています。');
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidTel
    //  概　要 : 電話番号の形式確認を返す
    //  引　数 : $tel1 : 電話番号（１つ目）
    //           $tel2tel3 : 電話番号（２つ目以降）
    public function ValidTel($tel1, $tel2tel3 = '')
    {
        // エラーメッセージ用変数を初期化
        $errMsg = '';
        // 分割用文字列が存在する
        if (strpos($tel2tel3, Base_lib::VALID_SEPARATE_STR) !== false) {
            $tel2tel3List = explode(Base_lib::VALID_SEPARATE_STR, $tel2tel3);
            if (
                $tel1 != '' &&
                (
                    $tel2tel3List[0] == '' ||
                    $tel2tel3List[1] == ''
                )
            ) {
                $errMsg = '{field}は必須です';
            } else {
                // 電話番号（入力内容をまとめる）
                $tel = $tel1 . $tel2tel3List[0] . $tel2tel3List[1];
            }
        } else {
            $tel = $tel1 . $tel2tel3;
        }
        if (isset($tel)) {
            // ハイフン削除処理
            $tel = str_replace(array('-', 'ー', '―', '‐'), '', $tel);
            // ７桁の数字
            if (
                $tel1 != '' &&
                ! preg_match("/^[\d]{10,}$/", $tel)
            ) {
                $errMsg = '形式が間違っています';
            }
        }

        // エラー文言が未セット
        if ($errMsg == '') {
            return true;
        } else {
            $this->set_message('ValidTel', $errMsg);
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidImage
    //  概　要 : 画像の形式確認を返す
    //  引　数 : $formName : フォーム名
    //           $srcFilePath : 保存ファイルパス
    public function ValidImage($formName, $srcFilePath = '')
    {
        $returnVal = true;
        // ファイルが未セット、かつ、登録済みのファイルが存在しない
        if (
            ((! isset($_FILES[$formName])) || $_FILES[$formName]['size'] == 0) &&
            ! $this->session_lib->GetSessionVal($formName) &&
            (! $srcFilePath || ($srcFilePath && ! $this->CI->upload_lib->FileExists($srcFilePath)))
        ) {
            $this->form_validation->set_message('ValidImage', 'ファイルを選択してください');
            $returnVal = false;
        }
        // ファイルの拡張子
        elseif (isset($_FILES[$formName]) && $_FILES[$formName]['size'] != 0) {
            // ライブラリー名の確認
            if ($libName) {
                // 対象ライブラリーを読込み
                $this->CI->load->library(ucfirst($libName));
                // 変数に代入
                $targetLib = $this->CI->{$libName};
            }
            // ファイルタイプ
            if (! in_array($_FILES[$formName]['type'], $this->CI->upload_lib->GetFileTypeImgList())) {
                $this->form_validation->set_message('ValidImage', '無効なファイルタイプです');
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
    public function ValidImageCheck($check, $formName)
    {
        $returnVal = true;

        $srcFilePath = '';

        // ファイルが未セット、かつ、登録済みのファイルが存在しない
        if (
            ((! isset($_FILES[$formName])) || $_FILES[$formName]['size'] == 0) &&
            ! $this->session_lib->GetSessionVal($formName) &&
            (! $srcFilePath || ($srcFilePath && ! $this->CI->upload_lib->FileExists($srcFilePath)))
        ) {
            $this->form_validation->set_message('ValidImage', 'ファイルを選択してください');
            $returnVal = false;
        }
        // ファイルの拡張子
        elseif (isset($_FILES[$formName]) && $_FILES[$formName]['size'] != 0) {
            // ライブラリー名の確認
            if ($libName) {
                // 対象ライブラリーを読込み
                $this->CI->load->library(ucfirst($libName));
                // 変数に代入
                $targetLib = $this->CI->{$libName};
            }
            // ファイルタイプ
            if (! in_array($_FILES[$formName]['type'], $this->CI->upload_lib->GetFileTypeImgList())) {
                $this->form_validation->set_message('ValidImage', '無効なファイルタイプです');
                $returnVal = false;
            }
        }
        return $returnVal;
    }
    //====================================================================
    //  関数名 : ValidSelectSheet
    //  概　要 : 座席が選択可能か返す
    //  引　数 : $sheetId : 座席ID
    //           $dateUserid : 日付｜利用者ID
    public function ValidSelectSheet($sheetId, $dateUserid = '')
    {
        // ライブラリー読込み
        $this->CI->load->library(Base_lib::MASTER_DIR . '/reserve_lib');
        // エラーメッセージ用変数を初期化
        $errMsg = '';
        // 分割用文字列が存在する
        if (strpos($dateUserid, Base_lib::VALID_SEPARATE_STR) !== false) {
            // 日付と利用者IDを配列に変換
            $dateUseridList = explode(Base_lib::VALID_SEPARATE_STR, $dateUserid);
            $date = $dateUseridList[0];
            $userId = $dateUseridList[1];

            if (! $this->CI->reserve_lib->SheetSameExists($sheetId, $date, $userId, Reserve_lib::ID_STATUS_TEMP)) {
                $errMsg = '既に他のお客様がご予約されています';
            }
        }
        // 座席と日付のみで取得確認
        elseif (! $this->CI->reserve_lib->SheetSameExists($sheetId, $dateUserid, '', Reserve_lib::ID_STATUS_TEMP)) {
            $errMsg = '既に他のお客様がご予約されています';
        }
        // エラー文言が未セット
        if ($errMsg == '') {
            return true;
        } else {
            $this->set_message('ValidSelectSheet', $errMsg);
            return false;
        }
    }
    //====================================================================
    //  関数名 : ValidError
    //  概　要 : 強制的にエラーを返す
    //  引　数 : $targetVal : 対象の値
    //           $errMsg : エラーメッセージ
    public function ValidError($targetVal, $errMsg = '')
    {
        $this->set_message('ValidError', ($errMsg != '' ? $errMsg : '内容をご確認ください'));
        return false;
    }
}
