<?php

/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Push {

    // push message title
    private $title;
    private $message;
	private $long_msg;
    private $image;
	private $noti_id;
	private $audio;
	private $video;
 
    private $date_;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct() {
        
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }
	
	 public function setMessage_long($long_msg) {
        $this->long_msg = $long_msg;
    }
	
    public function setImage($image) {
        $this->image = $image;
    }
	public function setAudio($audio) {
        $this->audio = $audio;
    }
    public function setVideo($video) {
        $this->video = $video;
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
        $res['data']['title'] = $this->title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['message'] = $this->message;
		$res['data']['long_msg'] = $this->long_msg;
		$res['data']['image'] = $this->image;
		$res['data']['audio'] = $this->audio;
		$res['data']['video'] = $this->video;
        $res['data']['payload'] = $this->data;
		$res['data']['noti_id'] = $this->noti_id;
		$res['data']['date'] = $this->date_;
        $res['data']['timestamp'] = date('d-M-Y G:i:s');
        return $res;
    }

}
