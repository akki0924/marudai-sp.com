<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * PDF生成文字列用サポートライブラリー
 *
 * PDF生成用の関数群
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     2021/05/25：新規作成
 */

 // ネームスペースを使用してクラスをインポート
use setasign\Fpdi\TcpdfFpdi;

class Pdf_lib
{
    // 読み込んだテンプレートのリスト
    protected $templates;
    // フォント
    // const FONT_PATH = "webfonts/ipag.ttf";
    const FONT_PATH = "webfonts/GenShinGothic-Bold.ttf";
    // 境界線
    const BORDER = 0; // 0 = 境界線なし, 1 = 境界線あり
    // 移動先
    const LN_RIGHT = 0;
    const LN_NEXT = 1;
    const LN_DOWN = 2;
    // 整列
    const ALIGN_LEFT = "L";
    const ALIGN_CENTER = "C";
    const ALIGN_RIGHT = "R";
    const ALIGN_JUST = "J";

    // １枚の注文商品最大値
    const MAX_PAGE_LIST = 13;

    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // サードパーティションライブラリを読み込み
        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
        require_once APPPATH . 'third_party/fpdi/autoload.php';
        // クラス宣言
        $pdf = new TcpdfFpdi();
        $pdf_font = new TCPDF_FONTS();

        // 各設定
        $pdf->SetMargins(0, 0, 0);        // 用紙の余白を設定
        $pdf->SetCellPadding(0);          // セルのパディングを設定
        $pdf->SetAutoPageBreak(false);    // 自動改ページ
        $pdf->setPrintHeader(false);      // ヘッダを使用しない
        $pdf->setPrintFooter(false);      // フッタを使用しない

        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // セット
        $this->CI->pdf = $pdf;
        $this->CI->pdf_font = $pdf_font;
    }
    /**
     * テンプレートPDFファイルをロードする。
     */
    public function myLoadTemplate($filePath)
    {
        $pageCount = $this->CI->pdf->setSourceFile($filePath);
        $templateId = array();
        for ($i = 0; $i < $pageCount; $i++) {
            $templateId[] = $this->CI->pdf->importPage($i + 1);
        }
        $this->templates = array(
            $filePath => array(
                'page_count'    => $pageCount,
                'template_id'   => $templateId,
            )
        );
    }
    /**
     * 指定したPDFファイルの指定したページをテンプレートとして使用する。
     *
     * @param string    $filePath   PDFファイルのパス
     * @param int       $page       ページ番号（1から）
     */
    public function myUseTemplate($filePath, $page = false)
    {
        if (! isset($this->templates[ $filePath ])) {
            $this->myLoadTemplate($filePath);
        }
        $this->CI->pdf->useTemplate($this->templates[$filePath]['template_id'][0]);
    }
}
