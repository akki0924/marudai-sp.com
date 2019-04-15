<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	■機　能： 定数データ取得プログラム
	■概　要： 定数データの取得および処理群
	■更新日： 2016/02/05
	■担　当： crew.miwa
	■更新履歴：
	 2016/02/05: 作成
	*/

define ("LIBRARY_MASTER_DIR", "master");
define ("DOCUMENT_PARENT_ROOT", "/usr/home/ad112hp62n/");
define ("TOP_DIR", "signage/");

// ホームページ
define ("HOME_PAGENAME", "home");
// アイドル時間（5分：1000ミリ秒 x 60秒 x 5分）
define ("IDLE_MILL_SEC", "300000");
// プルダウン
define ("DEFAULT_SELECT_FIRST_WORD", "▼選択して下さい");

// エラーメッセージ
define ("DEFAULT_NO_ITEM_MSG", "該当物件が見つかりませんでした。<br />別の条件で、再度検索しください。");
?>