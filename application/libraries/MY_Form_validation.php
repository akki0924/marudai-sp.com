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

class MY_Form_validation extends CI_Form_validation {
    // 表画面ログインチェック
    function PublicLogin ( $blank_data, $account, $password )
    {
       if( $this->CI->login_model->execute( Login_model::AUTH_OWNER, true, '', $password ) )
       {
          $this->set_message( 'unique_title',  '入力された情報は登録されておりません。' );
          return FALSE;
       }
       return FALSE;
    }
    // 管理画面ログインチェック
/*
    function AdminLogin ( $blank_data, $account, $password )
    {
print "account:" . $account . " pass:" . $password;
       if( $this->CI->login_model->execute( Login_model::AUTH_ADMIN, true, $account, $password ) )
       {
          $this->set_message( 'unique_title',  '入力された情報は登録されておりません。' );
          return FALSE;
       }
       return FALSE;
    }
*/
/*
    function AdminLogin ( $password, $account )
    {
print "account:" . $account . " pass:" . $password;
       if( $this->CI->login_model->execute( Login_model::AUTH_ADMIN, true, $account, $password ) )
       {
          $this->set_message( 'unique_title',  '入力された情報は登録されておりません。' );
          return FALSE;
       }
       return FALSE;
    }
*/
    function AdminLogin ( $password, $account )
    {
print "check_start:";
print "account:" . $account . " pass:" . $password;
       if( $this->CI->login_model->execute( Login_model::AUTH_ADMIN, true, $account, $password ) )
       {
          $this->set_message( 'admin_login',  '入力された情報は登録されておりません。' );
          return FALSE;
       }
       return FALSE;
    }
}
?>