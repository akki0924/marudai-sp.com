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

class Type_lib
{
    // ID
    const ID_MUSEUM = 1;
    const ID_GALLERY = 2;
    const ID_DEPARTMENT = 3;
    const ID_COLLEGE = 4;
    // 名前
    const NAME_MUSEUM = "美術館";
    const NAME_GALLERY = "ギャラリー";
    const NAME_DEPARTMENT = "デパート";
    const NAME_COLLEGE = "大学";
    
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
            self::ID_MUSEUM     => self::NAME_MUSEUM,
            self::ID_GALLERY    => self::NAME_GALLERY,
            self::ID_DEPARTMENT => self::NAME_DEPARTMENT,
            self::ID_COLLEGE    => self::NAME_COLLEGE
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