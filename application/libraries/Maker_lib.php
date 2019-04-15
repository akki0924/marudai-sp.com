<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： 製作者用処理ライブラリー
    ■概　要： 製作者用登録関数群
    ■更新日： 2018/10/31
    ■担　当： crew.miwa

    ■更新履歴：
     2018/10/31: 作成開始
     
    */

class Maker_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_maker";
    
    
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
                " . self::MASTER_TABLE . ".status,
                " . self::MASTER_TABLE . ".regist_date,
                DATE_FORMAT(" . self::MASTER_TABLE . ".regist_date, '%Y.%c.%e') AS regist_date_disp,
                " . self::MASTER_TABLE . ".edit_date,
                DATE_FORMAT(" . self::MASTER_TABLE . ".edit_date, '%Y.%c.%e') AS edit_date_disp
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
        概　要： 製作者名一覧を取得
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
        関数名： GetNameToId
        概　要： 名前からIDを取得
    */
    public function GetNameToId ( $name, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'id', $name, 'name', $public );
    }
    /*====================================================================
        関数名： GetIdOrNameToId
        概　要： ID、または名前からIDを取得
    */
    public function GetIdOrNameToId ( $id = '', $name = '', $public = false )
    {
        // IDが登録されておらず、名前がセットされている場合
        if ( ! $this->IdExists ( $id, $public ) && isset( $name ) )
        {
            // 名前からIDを取得
            $returnVal = $this->GetNameToId ( $name, $public );
        }
        else
        {
            // IDをそのまま返す
            $returnVal = $id;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetRankId
        概　要： ランクを取得
    */
    public function GetRankId ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'rank_id', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetTag
        概　要： タグ情報を取得
    */
    public function GetTag ( $id, $array_flg = false, $public = false )
    {
        // 値を取得
        $returnVal = $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'tag', $id, 'id', $public );
        // 配列フラグがセットされている場合
        if ( $array_flg )
        {
            // JSON文字列をデコードして返す
            $returnVal = json_decode ( $returnVal, true );
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetAddTagJson
        概　要： タグ情報をDB登録されているタグ情報に追加し取得
    */
    public function GetAddTagJson ( $id = "", $tag, $public = false )
    {
        // 登録された情報の場合
        if ( $this->IdExists ( $id, $public ) )
        {
            // 登録されたタグ情報をセット
            $tag_base = $this->GetTag ( $id, true, $public );
        }
        // 登録されたタグが存在する場合
        if (  isset ( $tag_base ) && count ( $tag_base ) > 0 )
        {
            // 返値用配列にセット
            $returnList = $tag_base;
            
            // セットされたタグを確認
            if ( isset ( $tag ) && count ( $tag ) > 0 )
            {
                foreach ( $tag as $tag_val )
                {
                    // 登録されたタグに、セットされるタグが存在しなければ
                    if ( ! in_array ( $tag_val, $tag_base ) )
                    {
                        // 返値用配列に値を追加
                        $returnList[] = $tag_val;
                    }
                }
            }
        }
        else
        {
            // タグ情報をそのままセット
            $returnList = $tag;
        }
        // JSONデータに変換して返す
        return json_encode ( $returnList );
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
        関数名： Regist
        概　要： DB登録処理
    */
    public function Regist ( $id = '', $values )
    {
        // ID、または名前からIDを取得
        $id = $this->GetIdOrNameToId ( $id, $values['name'], true );
        // タグ情報がセットされている場合
        if( isset( $values['tag'] ) )
        {
            // タグ情報をJSON形式に追加セット
            $values['tag'] = $this->GetAddTagJson ( $id, $values['tag'], true );
        }
        // IDが登録されている場合
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
        else
        {
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
        }
    }
    /*====================================================================
        関数名： RegistName
        概　要： DB登録処理
    */
    public function RegistName ( $id = '', $name, $public = false )
    {
        // ID、または名前からIDを取得
        $id = $this->GetIdOrNameToId ( $id, $name, $public );
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
    public function RegistForName ( $name, $public = false  )
    {
        // 製作者名が登録されていない時に限り、登録処理
        if ( ! $this->NameExists ( $name ) )
        {
            $regist_data['name'] = $name;   // 製作者名
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $regist_data );
        }
    }
    /*====================================================================
        関数名： EditRankId
        概　要： DB更新処理（ランク変更）
    */
    public function EditRankId ( $id, $rank_id )
    {
        // 製作者IDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新情報をセット
            $regist_data['rank_id'] = $rank_id;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $regist_data, $id );
        }
    }
    /*====================================================================
        関数名： SelectDelete
        概　要： DB削除処理（論理削除）
    */
    public function SelectDelete ( $id )
    {
        // 製作者IDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            return $this->CI->db_lib->Delete ( self::MASTER_TABLE, true, $id );
        }
    }
}
?>