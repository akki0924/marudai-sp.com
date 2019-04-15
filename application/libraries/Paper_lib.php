<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： 紙面用処理ライブラリー
    ■概　要： 紙面用登録関数群
    ■更新日： 2019/01/18
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/18: 作成開始
     
    */

class Paper_lib
{
    // ID
    const ID_1 = "1";
    const ID_2 = "2";
    const ID_3 = "3";
    // 名前
    const NAME_1 = "1";
    const NAME_2 = "2";
    const NAME_3 = "3";
    
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
        関数名： GetValues
        概　要： データ一覧を取得
    */
    public function GetValues ()
    {
        // 返値を初期化
        $resultList = array();
        
        $resultList = array (
            self::ID_1 => self::NAME_1,
            self::ID_2 => self::NAME_2,
            self::ID_3 => self::NAME_3
        );
        return $resultList;
    }
    /*====================================================================
        関数名： ListValues
        概　要： データ一覧を取得
    */
    public function ListValues ( $public = false )
    {
        return $this->GetValues ();
    }
    /*====================================================================
        関数名： GetName
        概　要： 名前を取得
    */
    public function GetName ( $id, $public = false )
    {
        $returnVal = "";
        if ( $this->IdExists ( $id ) )
        {
            $list = $this->ListValues ();
            $returnVal = $list[$id];
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetNameToId
        概　要： 名前からIDを取得
    */
    public function GetNameToId ( $name, $public = false )
    {
        $returnVal = "";
        if ( $this->NameExists ( $name ) )
        {
            $list = $this->ListValues ();
            $returnVal = array_search ( $name, $list );
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： IdExists
        概　要： IDが存在するかどうか
    */
    public function IdExists ( $id, $public = false )
    {
        $list = $this->ListValues ();
        return isset ( $list[$id] );
    }
    /*====================================================================
        関数名： NameExists
        概　要： 名前が存在するかどうか
    */
    public function NameExists ( $name, $public = false )
    {
        $list = $this->ListValues ();
        return in_array ( $name, $list[$id], true );
    }
}
?>