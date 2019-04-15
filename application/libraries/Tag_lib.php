<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： タグ用処理ライブラリー
    ■概　要： タグ用登録関数群
    ■更新日： 2018/10/24
    ■担　当： crew.miwa

    ■更新履歴：
     2018/10/24: 作成開始
     
    */

class Tag_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_tag";
    
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
        関数名： DetalVal
        概　要： 詳細データを取得
    */
    public function DetalVal ( $id, $public = false )
    {
        $returnVal = array ();
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . ".id,
                " . self::MASTER_TABLE . ".name,
                " . self::MASTER_TABLE . ".sort_id,
                " . self::MASTER_TABLE . ".status,
                " . self::MASTER_TABLE . ".regist_date,
                DATE_FORMAT(" . self::MASTER_TABLE . ".regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . self::MASTER_TABLE . ".update_date,
                DATE_FORMAT(" . self::MASTER_TABLE . ".update_date, '%Y.%c.%e') AS update_date_disp
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . ".id = " . Base::empty_to_null( $id ) . "
                " . ( $public ? " AND " . self::MASTER_TABLE . ".status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0) {
            $result_list = $query->result_array();
            foreach ($result_list[0] as $key => $val) {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： SelectNameValues
        概　要： タグ名一覧を取得
    */
    public function SelectNameValues ( $public = false )
    {
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： GetName
        概　要： 名前を取得
    */
    public function GetName ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'name', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetIdToName
        概　要： 名前からIDを取得
    */
    public function GetIdToName ( $name, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'id', $name, 'name', $public );
    }
    /*====================================================================
        関数名： GetSortId
        概　要： 順番を取得
    */
    public function GetSortId ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'sort_id', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetSortMax
        概　要： 順番最大値を取得
    */
    public function GetSortMax ( $public = false )
    {
        return $this->CI->db_lib->GetValueMax ( self::MASTER_TABLE, 'sort_id', $public );
    }
    /*====================================================================
        関数名： IdExists
        概　要： IDが存在するかどうか
    */
    public function IdExists ( $id, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $id, 'id', $public );
    }
    /*====================================================================
        関数名： NameExists
        概　要： 名前が存在するかどうか
    */
    public function NameExists ( $name, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $name, 'name', $public );
    }
    /*====================================================================
        関数名： RegistName
        概　要： DB登録処理
    */
    public function RegistName ( $id = '', $name )
    {
        // タグIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新情報をセット
            $regist_data['name'] = $name;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $regist_data, $id );
        }
        else 
        {
            // DB登録処理（名前から登録）メソッドを実行
            $this->RegistForName ( $name );
        }
    }
    /*====================================================================
        関数名： RegistForName
        概　要： DB登録処理（名前から登録）
    */
    public function RegistForName ( $name )
    {
        // タグ名が登録されていない時に限り、登録処理
        if ( ! $this->NameExists ( $name ) )
        {
            $regist_data['name'] = $name;   // タグ名
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $regist_data );
        }
    }
    /*====================================================================
        関数名： EditSortId
        概　要： DB更新処理（順番変更）
    */
    public function EditSortId ( $id, $sort_id_set )
    {
        // タグIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 現在の順番
            $sort_id_now = $this->GetSortId ( $id );
            
            // 更新情報をセット (エスケープ処理を回避)
            $this->CI->db->set ( 'sort_id', 'sort_id' . ( $sort_id_set > $sort_id_now ? '-' : '+' ) . '1', false );
            // 対象ID以外の情報を更新
            $this->CI->db->where ( 'id !=', $id );
            $this->CI->db->where ( 'sort_id ' . ( $sort_id_set > $sort_id_now ? '<=' : '>=' ), $sort_id_set );
            $this->CI->db->where ( 'sort_id ' . ( $sort_id_set > $sort_id_now ? '>=' : '<=' ), $sort_id_now );
            $this->CI->db->where ( 'status', Base::STATUS_ENABLE );
            $this->CI->db->update ( self::MASTER_TABLE );
            
            // 更新情報をセット
            $this->CI->db->set ( 'sort_id', $sort_id_set );
            // 対象情報を更新
            $this->CI->db->where ( 'id', $id );
            $this->CI->db->update ( self::MASTER_TABLE );
        }
    }
    /*====================================================================
        関数名： SelectDelete
        概　要： DB削除処理（論理削除）
    */
    public function SelectDelete ( $id )
    {
        // タグIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            return $this->CI->db_lib->Delete ( self::MASTER_TABLE, true, $id );
        }
    }
}
?>