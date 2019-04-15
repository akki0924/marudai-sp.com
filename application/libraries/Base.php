<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	■機　能： ベース用マスタ処理プログラム
	■概　要： 初期設定定数、コモン関数などのプログラム
	■更新日： 2015/06/23
	■担　当： crew.miwa

	■更新履歴：
	 2015/06/23: 作成開始
	 
	*/

class Base {
	
	const STATUS_ENABLE = 1;
	const STATUS_TEMP = 0;
	const STATUS_DISABLE = -1;

	const DEFAULT_SELECT_FIRST_WORD = "▼選択して下さい";
	
    /*====================================================================
        関数名： SelectboxDefalutForm
        概　要： 配列をプルダウン用に形に成形してはき出す
    */
    public static function SelectboxDefalutForm($array, $default_word = ""){
        $default_array[''] = ($default_word ? $default_word : self::DEFAULT_SELECT_FIRST_WORD);
        $array = $default_array + $array;
        
        return $array;
    }
	/*====================================================================
		関数名： now
		概　要： DB登録用の現在日時をセット
	*/
	public static function now(){
		return date('Y-m-d H:i:s');
	}
	/*====================================================================
		関数名： add_slashes
		概　要： 文字列をスラッシュでクォートする
	*/
	public static function add_slashes($str){
		$str = addslashes($str);
		$str = preg_replace ("/\\'/", "'", $str);

		$str = str_replace ("\'", "'", $str);
		$str = str_replace ("'", "''", $str);
		$str = str_replace("\r\n", "\n", $str);
		$str = str_replace("\r", "\n", $str);
		$str = str_replace(";", "\\;", $str);

		return $str;
	}

	/*====================================================================
		関数名： empty_to_null
		概　要： 文字列をスラッシュでクォートする
	*/
	public static function empty_to_null ($data) {
		if (($data == "" && $data !==  0) || !isset($data)) {
			$data = "NULL";
		}
		return self::add_slashes($data);
	}

}
?>