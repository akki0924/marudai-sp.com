<?php
    /*
    ■機　能： Email情報用ライブラリ
    ■概　要： Email情報用関連全般
    ■更新日： 2020/06/22
    ■担　当： crew.miwa

    ■更新履歴：
     2020/06/22: 作成開始

    */

class Mail_db
{
    // メンバー変数
    protected $CI;              // スーパーオブジェクト割当用
    private $targetFrom;        // 送信元
    private $targetTo;          // 送信先
    private $targetCc;          // CC
    private $targetBcc;         // BCC
    private $targetSubject;     // 件名
    private $targetMessage;     // 本文
    private $sendFlg = true;    // 送信可否フラグ
    
    /*====================================================================
        コントラクト
    */
    public function __construct()
    {
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
        // ライブラリー読込み
        $this->CI->load->library('email');
    }
    /*====================================================================
        関数名： from
        概　要： FROM情報をセット
    */
    public function from ( $targetFrom = '' )
    {
        $this->targetFrom = $targetFrom;
    }
    /*====================================================================
        関数名： getFrom
        概　要： FROM情報を取得
    */
    public function getFrom ()
    {
        return $this->targetFrom;
    }
    /*====================================================================
        関数名： to
        概　要： FROM情報をセット
    */
    public function to ( $targetTo = '' )
    {
        $this->targetTo = $targetTo;
    }
    /*====================================================================
        関数名： getTo
        概　要： FROM情報を取得
    */
    public function getTo ()
    {
        return $this->targetTo;
    }
    /*====================================================================
        関数名： cc
        概　要： FROM情報をセット
    */
    public function cc ( $targetCc = '' )
    {
        $this->targetCc = $targetCc;
    }
    /*====================================================================
        関数名： getCc
        概　要： FROM情報を取得
    */
    public function getCc ()
    {
        return $this->targetCc;
    }
    /*====================================================================
        関数名： bcc
        概　要： FROM情報をセット
    */
    public function bcc ( $targetBcc = '' )
    {
        $this->targetBcc = $targetBcc;
    }
    /*====================================================================
        関数名： getBcc
        概　要： FROM情報を取得
    */
    public function getBcc ()
    {
        return $this->targetBcc;
    }
    /*====================================================================
        関数名： subject
        概　要： FROM情報をセット
    */
    public function subject ( $targetSubject = '' )
    {
        $this->targetSubject = $targetSubject;
    }
    /*====================================================================
        関数名： getSubject
        概　要： FROM情報を取得
    */
    public function getSubject ()
    {
        return $this->targetSubject;
    }
    /*====================================================================
        関数名： message
        概　要： FROM情報をセット
    */
    public function message ( $targetMessage = '' )
    {
        $this->targetMessage = $targetMessage;
    }
    /*====================================================================
        関数名： getMessage
        概　要： FROM情報を取得
    */
    public function getMessage ()
    {
        return $this->targetMessage;
    }
    /*====================================================================
        関数名： send
        概　要： FROM情報をセット
    */
    public function send ()
    {
        $returnVal = false;
        if (
            $this->getFrom() != "" &&
            $this->getTo() != "" &&
            $this->getSubject() != "" &&
            $this->getMessage() != ""
        ) {
            // メール送信情報をセット
            $this->CI->email->from( $this->getFrom() );
            $this->CI->email->to( $this->getTo() );
            $this->CI->email->subject( $this->getSubject() );
            $this->CI->email->message( $this->getMessage() );
            if ( $this->getCc() != '' ) $this->CI->email->cc( $this->getCc() );
            if ( $this->getBcc() != '' ) $this->CI->email->bcc( $this->getBcc() );
            // 自動折り返し処理
            $this->CI->email->set_wordwrap( false );
            
            // 送信処理
            $returnVal = $this->CI->email->send();
        }
        return $returnVal;
    }
}
