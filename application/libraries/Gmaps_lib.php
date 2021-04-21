<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
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
class Gmaps_lib extends Base_lib
{

    // URL
    const URL_JS_API = "https://maps.googleapis.com/maps/api/js";
    const URL_GEOCODING_API = "https://maps.googleapis.com/maps/api/geocode/json";
    // GoogleMapsキー
    const G_KEY = "AIzaSyD0vrie4s8atAppliWYiwRl4I7_PhmTLC0";
    // 取得用配列キー
    const LAT_KEY = "lat";
    const LNG_KEY = "lng";
    // 小数点まとめる単位
    const POINT_UNIT = 7;
    // Google Map初期値
    const DEFAULT_LAT = 35.150138253421346;     // 緯度
    const DEFAULT_LNG = 136.95653063564117;     // 経度
    const DEFAULT_ZOOM = 11;                    // ズーム
    // 表示範囲
    const AREA_RANGE_NORTH = 35.35;             // 北
    const AREA_RANGE_SOUTH = 34.97;             // 南
    const AREA_RANGE_WEST = 136.7;              // 西
    const AREA_RANGE_EAST = 137.2;              // 東

    /*====================================================================
        関数名： addToPoint
        概　要： GoogleMap Keyと住所から、緯度と経度を取得
        引　数： $address:住所、$japanFlg:緯度経度を日本測地系にするかどうか
        戻り値： なし
    */
    public function addToPoint($address = null, $japanFlg = false)
    {
        if (! $address) {
            return null;
        }
        // GOOGLEより情報を取得
        $rs = file_get_contents(self::URL_GEOCODING_API . "?key=" . self::G_KEY . "&address=" . $address . "&sensor=false");
        // 情報が取得出来ている場合
        if ($rs) {
            // JSONから配列に変換
            $rs_array = json_decode($rs, true);
            $status = $rs_array['status'];
            $lat = $rs_array['results']['0']['geometry']['location']['lat'];
            $lng = $rs_array['results']['0']['geometry']['location']['lng'];
//          list($status,,$lat, $lng) = explode(",", $rs);
            if ($status == "OK") {
                // 緯度経度を日本測地系にするかどうか
                if ($japanFlg) {
                    // 緯度経度を世界測地系から、日本測地系へと変換
                    $lat_j = ($lat * 1.00010696) - ($lng * 0.000017467) - 0.0046020;
                    $lng_j = ($lng * 1.000083049) + ($lat * 0.000046047) - 0.010041;
                    // 小数点以下をまとめる
                    $lat = round($lat_j, self::POINT_UNIT);
                    $lng = round($lng_j, self::POINT_UNIT);
                }
                // 返値にセット
                $returnVal[self::LAT_KEY] = $lat;
                $returnVal[self::LNG_KEY] = $lng;

                return ($returnVal);
            }
        }
    }
}
