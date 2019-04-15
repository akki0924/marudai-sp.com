<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： 個人ファイル用ライブラリー
    ■概　要： 個人ファイル用関数群
    ■更新日： 2018/11/06
    ■担　当： crew.miwa

    ■更新履歴：
     2018/11/06: 作成開始
     
    */

class My_file_lib
{
    // 媒介用定数
    const MOVIE_ADULT_NAME = '成年動画';
    const MOVIE_NORMAL_NAME = '一般動画';
    const MOVIE_FILM_NAME = '映画';
    const MOVIE_DRANA_NAME = 'ドラマ';
    const ANIME_ADULT_NAME = '成年アニメ';
    const ANIME_NORMAL_NAME = '一般アニメ';
    const COMIC_ADULT_NAME = '成年コミック';
    const COMIC_FANZINE_NAME = '同人コミック';
    const COMIC_NORMAL_NAME = '一般コミック';
    const MUSIC_EXT_NAME = 'mp3';
    const MOVIE_EXT_NAME1 = 'mp4';
    const MOVIE_EXT_NAME2 = 'flv';
    const MOVIE_EXT_NAME3 = 'avi';
    const MOVIE_EXT_NAME4 = 'wmv';
    
    // 製作者用定数
    const MAKER_ANTHOL_NAME = 'アンソロジー';
    
    // 対象ベースディレクトリ
    const DIR_BASE = 'C:\Users\miwa-PC2\Downloads';
}
?>