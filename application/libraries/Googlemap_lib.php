<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： GoogleMap用ライブラリ
    ■概　要： GoogleMap用関連全般
    ■更新日： 2019/02/14
    ■担　当： crew.miwa

    ■更新履歴：
     2019/02/14: 作成
    */

/*====================================================================
    クラス名： GoogleMap
    概　　要： GoogleMapAPIを利用した処理クラス
*/
class Googlemap_lib {
    
    // URL
    const URL = "https://maps.googleapis.com/maps/api/geocode/json";
    // GoogleMapsキー
    const G_KEY = "AIzaSyDGcUvTTEllxnwANq0-KsRrc9hGR6il2b8";
    // 取得用配列キー
    const LAT_KYE = "lat";
    const LNG_KEY = "lng";
    // 小数点まとめる単位
    const POINT_UNIT = 7;
    /*====================================================================
         関数名： addToPoint
         概　要： GoogleMap Keyと住所から、緯度と経度を取得
         引　数： $address=住所、$japan_flg=緯度経度を日本測地系にするかどうか
         戻り値： なし
    */
    public function addToPoint ( $address = null, $japan_flg = false ) {
        if ( ! $address ) return null;
        // GOOGLEより情報を取得
        $rs = file_get_contents( self::URL . "?key=" . self::G_KEY . "&address=" . $address . "&sensor=false" );
        // 情報が取得出来ている場合
        if ( $rs )
        {
            // JSONから配列に変換
            $rs_array = json_decode ( $rs, true );
            $status = $rs_array['status'];
            $lat = $rs_array['results']['0']['geometry']['location']['lat'];
            $lng = $rs_array['results']['0']['geometry']['location']['lng'];
//          list($status,,$lat, $lng) = explode(",", $rs);
            if ( $status == "OK" )
            {
                // 緯度経度を日本測地系にするかどうか
                if ( $japan_flg )
                {
                    // 緯度経度を世界測地系から、日本測地系へと変換
                    $lat_j = ( $lat * 1.00010696 ) - ( $lng * 0.000017467 ) - 0.0046020;
                    $lng_j = ( $lng * 1.000083049 ) + ( $lat * 0.000046047 ) - 0.010041;
                    // 小数点以下をまとめる
                    $lat = round ( $lat_j, self::POINT_UNIT );
                    $lng = round ( $lng_j, self::POINT_UNIT );
                }
                // 返値にセット
                $returnVal[self::LAT_KYE] = $lat;
                $returnVal[self::LNG_KEY] = $lng;
                
                return ( $returnVal );
            }
        }
    }
}
?>