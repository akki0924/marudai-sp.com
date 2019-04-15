<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： SESSION用サポート処理ライブラリー
    ■概　要： 登録、更新、削除などのSESSION登録関数群
    ■更新日： 2018/01/19
    ■担　当： crew.miwa

    ■更新履歴：
     2018/01/19: 作成開始
     
    */

class Session_lib
{
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
        関数名： GetSessionVal
        概　要： SESSION登録されている情報を取得
        引　数： $key_name : SESSIONキー
    */
    public function GetSessionVal ( $key_name )
    {
        return $this->CI->session->userdata( $key_name );
    }
    /*====================================================================
        関数名： SessionValExists
        概　要： 値が存在するかどうか
        引　数： $key_name : SESSIONキー
    */
    public function SessionValExists ( $key_name )
    {
        return ( $this->CI->session->userdata( $key_name ) != "" ? true : false);
    }
    /*====================================================================
        関数名： SetSessionVal
        概　要： SESSION登録されている情報の登録
        引　数： $key_name : SESSIONキー
                 $value : 登録する値
    */
    public function SetSessionVal ( $key_name, $value )
    {
        $set_values = array (
            $key_name => $value
        );
        return $this->CI->session->set_userdata($set_values);
    }
    /*====================================================================
        関数名： ClearSessionVal
        概　要： SESSION登録されている情報を削除
        引　数： $key_name : SESSIONキー
    */
    public function ClearSessionVal ( $key_name )
    {
        return $this->CI->session->unset_userdata( $key_name );
    }
}
?>