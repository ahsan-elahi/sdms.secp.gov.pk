<?php

/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Push {

    // push message title
    private $notifi_title;
    private $notifi_descp;
	private $notifi_type;
	private $notifi_id;
	private $notifi_pin;
	private $noti_id; 
    private $date_;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct() {
        
    }

    public function setTitle($notifi_title) {
        $this->notifi_title = $notifi_title;
    }

    public function setMessage($notifi_descp) {
        $this->notifi_descp = $notifi_descp;
    }
	public function setType($notifi_type) {
        $this->notifi_type = $notifi_type;
    }
	public function setID($notifi_id) {
        $this->notifi_id = $notifi_id;
    }
	public function setPin($notifi_pin) {
        $this->notifi_pin = $notifi_pin;
    }
	public function setdate($date_) {
        $this->date_ = $date_;
    }
	
	public function notification_id($noti_id) {
        $this->noti_id = $noti_id;
    }

    public function setPayload($data) {
        $this->data = $data;
    }

    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }

    public function getPush() {
        $res = array();
        $res['data']['notifi_title'] = $this->notifi_title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['notifi_descp'] = $this->notifi_descp;
		$res['data']['notifi_type'] = $this->notifi_type;
		$res['data']['notifi_id'] = $this->notifi_id;
		$res['data']['notifi_pin'] = $this->notifi_pin;
        $res['data']['payload'] = $this->data;
		$res['data']['noti_id'] = $this->noti_id;
		$res['data']['date'] = $this->date_;
		$res['data']['time'] = date('h:i:s a');
        return $res;
    }

}