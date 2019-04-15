<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： トピックス用処理ライブラリー
    ■概　要： トピックス用登録関数群
    ■更新日： 2019/01/18
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/18: 作成開始
     
    */

class Topics_lib
{
    // DBテーブル
    const MASTER_TABLE = "d_place_topics";
    // 表示数
    const DEFAULT_LIST_COUNT = 30;
    // ステータスID
    const STATUS_ENABLE_ID = 1;
    const STATUS_DISABLE_ID = -1;
    // ステータス名
    const STATUS_ENABLE_NAME = "ON";
    const STATUS_DISABLE_NAME = "OFF";
    
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
        // 各ライブラリの読み込み
        $this->CI->load->library( 'place_lib' );
        
        // 返値を初期化
        $returnVal = array ();
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . place_id,
                " . self::MASTER_TABLE . " . paper_type,
                " . self::MASTER_TABLE . " . title,
                " . self::MASTER_TABLE . " . title_sub,
                DATE_FORMAT(" . self::MASTER_TABLE . " . start, '%Y.%c.%e') AS start,
                DATE_FORMAT(" . self::MASTER_TABLE . " . end, '%Y.%c.%e') AS end,
                " . self::MASTER_TABLE . " . closing,
                " . self::MASTER_TABLE . " . body,
                " . self::MASTER_TABLE . " . next,
                " . self::MASTER_TABLE . " . memo,
                " . self::MASTER_TABLE . " . caption,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . ".edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . ".id = " . Base::empty_to_null( $id ) . "
                " . ( $public ? " AND " . self::MASTER_TABLE . ".status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $result_list = $query->result_array();
            foreach ( $result_list[0] as $key => $val )
            {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： ListValues
        概　要： データ一覧を取得
    */
    public function ListValues ( $place_id = '', $public = false )
    {
        // 各ライブラリの読み込み
        $this->CI->load->library( 'place_lib' );
        
        // 返値を初期化
        $resultList = array();
        
        // WHERE文をセット
        if ( $place_id )
        {
            $whereSql[] = self::MASTER_TABLE . " . place_id = " . $this->CI->base->empty_to_null ( $place_id );
        }
        if ( $public )
        {
            $whereSql[] = self::MASTER_TABLE . " . status > " . Base::STATUS_DISABLE;
        }
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . place_id,
                " . Place_lib::MASTER_TABLE . " . name AS place_name,
                " . self::MASTER_TABLE . " . title,
                " . self::MASTER_TABLE . " . title_sub,
                DATE_FORMAT(" . self::MASTER_TABLE . " . start, '%Y年%c月%e日') AS start_disp,
                DATE_FORMAT(" . self::MASTER_TABLE . " . end, '%Y年%c月%e日') AS end_disp,
                " . Place_lib::MASTER_TABLE . " . address AS place_address,
                " . Place_lib::MASTER_TABLE . " . tel AS place_tel,
                " . self::MASTER_TABLE . " . status
            FROM " . self::MASTER_TABLE . "
            LEFT OUTER JOIN " . Place_lib::MASTER_TABLE . " ON
                " . self::MASTER_TABLE . " . place_id = " . Place_lib::MASTER_TABLE . " . id
            " . ( isset ( $whereSql ) && count ( $whereSql ) > 0 ? ( " WHERE ( " . @implode ( " AND ", $whereSql ) ) . " ) " : "" ) . "
            ORDER BY " . self::MASTER_TABLE . " . sort_id ASC;
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $resultList = $query->result_array();
            for ( $i = 0, $count_i = 0, $n = count ($resultList); $i < $n; $i ++ )
            {
                // 表示数をカウント
                $count_i ++;
                // 表示件数
                $pageVal['dispLine'] = $count_i;
/*
// 表示数の固定処理をコメントアウト
                // 表示数が最大値まで来たら、ループ終了
                if ($count_i >= self::DEFAULT_LIST_COUNT) {
                    break;
                }
*/
            }
        }
        
        return $resultList;
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
        関数名： GetNameToId
        概　要： 名前からIDを取得
    */
    public function GetNameToId ( $name, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'id', $name, 'name', $public );
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
        関数名： PlaceIdExists
        概　要： 会場IDが存在するかどうか
    */
    public function PlaceIdExists ( $id, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $id, 'place_id', $public );
    }
    /*====================================================================
        関数名： GetStatusValues
        概　要： ステータス一覧を取得
    */
    public function GetStatusValues ()
    {
        // 返値を初期化
        $resultList = array();
        
        $resultList = array (
            self::STATUS_ENABLE_ID  => self::STATUS_ENABLE_NAME,
            self::STATUS_DISABLE_ID => self::STATUS_DISABLE_NAME,
        );
        return $resultList;
    }
    /*====================================================================
        関数名： StatusListValues
        概　要： ステータス一覧を取得
    */
    public function StatusListValues ( $public = false )
    {
        return $this->GetStatusValues ();
    }
    /*====================================================================
        関数名： GetStatusName
        概　要： ステータス名を取得
    */
    public function GetStatusName ( $id, $public = false )
    {
        // 返値を初期化
        $returnVal = "";
        // 一覧を取得
        $list = $this->StatusListValues ();
        $returnVal = ( isset ( $list[$id] ) ? $list[$id] : "" );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： Regist
        概　要： DB登録処理
    */
    public function Regist ( $id = '', $values )
    {
        $returnVal = "";
        // IDが登録されている場合
        if ( $this->IdExists ( $id ) )
        {
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
            // 返値をセット
            $returnVal = $id;
        }
        else
        {
            // IDを生成
            $values['id'] = $this->CI->db_lib->CreateSortId ( self::MASTER_TABLE, 'id' );
            // IDを生成
            $values['status'] = self::STATUS_DISABLE_ID;
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
            // 返値をセット
            $returnVal = $values['id'];
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： EditSort
        概　要： DB更新処理（順番変更）
    */
    public function EditSort ( $id, $sort )
    {
        // IDが登録されている場合
        if ( $this->IdExists ( $id ) )
        {
            $values['sort_id'] = $sort;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
    }
    /*====================================================================
        関数名： SelectDelete
        概　要： DB削除処理（論理削除）
    */
    public function SelectDelete ( $id )
    {
        // トピックスIDが登録されているか
        if ( $this->IdExists ( $id ) )
        {
            // 更新処理
            return $this->CI->db_lib->Delete ( self::MASTER_TABLE, false, $id );
        }
    }
}
?>