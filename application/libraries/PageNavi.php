<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/*
	■機　能： ページナビデータ作成プログラム
	■概　要： 取得数、表示数、ページ数に応じてページナビ用の処理群
	■更新日： 2016/02/04
	■担　当： crew.miwa
	■更新履歴：
	 2016/02/04: 作成
	*/


class PageNavi {
	
	const DEFAULT_PAGE = 1;        // 初期ページ
	const DEFAULT_LIST_COUNT = 5;  // 表示件数
	const DEFAULT_NAVI_COUNT = 5;  // ページナビ数
	/*====================================================================
		関数名： GetList
		概　要： ページデータ一覧を配列として返す
	*/
	public static function GetValeus ($maxLine, $page = "", $list_count = "") {
	
		// 最大件数
		$returnVal['maxLine'] = $maxLine;
		// 現ページ情報をセット
		$returnVal['page'] = ($page ? $page : self::DEFAULT_PAGE);
		// 表示数
		$list_count = ($list_count ? $list_count : self::DEFAULT_LIST_COUNT);
		// ページナビ数
		$page_navi_count = self::DEFAULT_NAVI_COUNT;
		
		// リストの最初のアイテム
//		$listStartItem = (($returnVal['page'] - 1) * $list_count) + 1;
		
		// 最大ページをセット
		if ($list_count > 0) {
			$returnVal['maxPage'] = ceil($returnVal['maxLine'] / $list_count) + (ceil($returnVal['maxLine'] / $list_count) > 0 ? 0 : 1);
		}
		
		// ページナビ数が半分をセット
		$navi_half = floor($page_navi_count / 2);
		
		// ページジャンプ用の開始番号をセット
		$linkStartPage = $returnVal['page'] - $navi_half;
		if ($linkStartPage <= 0) {
			// 1ページ目を下回る場合、ページ1を再セット
			$linkStartPage = self::DEFAULT_PAGE;
		}
		// ページジャンプ用の終了番号をセット
		$linkEndPage = $returnVal['page'] + $navi_half;
		if ($linkEndPage >= $returnVal['maxPage']) {
			if ($linkStartPage > self::DEFAULT_PAGE) {
				// 最小ページを再セット
				$linkStartPage = $linkStartPage - ($linkEndPage - $returnVal['maxPage']);
				$linkStartPage = ($linkStartPage > 0 ? $linkStartPage : self::DEFAULT_PAGE);
			}
			// 最大ページを上回る場合、最大ページを再セット
			$linkEndPage = $returnVal['maxPage'];
		}
		// ページ矢印をセット
		$returnVal['link_prev'] = ($returnVal['page'] > self::DEFAULT_PAGE ? ($returnVal['page'] - 1) : false);
		$returnVal['link_next'] = ($returnVal['page'] < $returnVal['maxPage'] ? ($returnVal['page'] + 1) : false);
		
		// ページ情報
		for ($i = 0; $i < $page_navi_count; $i ++) {
			$returnVal['link'][$i]['page'] = $linkStartPage + $i;
			$returnVal['link'][$i]['active'] = ((($linkStartPage + $i) == $returnVal['page']) ? true : false);
			if (($linkStartPage + $i) >= $returnVal['maxPage']) {
				break;
			}
		}
		
		return $returnVal;
	}

}
?>